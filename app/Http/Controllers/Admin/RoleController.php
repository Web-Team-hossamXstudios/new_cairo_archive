<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BulkActionRequest;
use App\Http\Requests\Admin\RoleRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;

/**
 * RoleController
 *
 * Manages user roles and permissions using Spatie Permission package.
 * Handles role CRUD operations, permission assignment, and hierarchical permission grouping.
 * Provides both flat and hierarchical permission views for better organization.
 * Protects system roles (super-admin, admin) from deletion or modification.
 *
 * @package App\Http\Controllers\Admin\Users
 */
class RoleController extends Controller
{
    /**
     * Display a listing of roles
     *
     * Shows paginated list with user count and assigned permissions.
     * Includes hierarchical permission grouping by module.
     *
     * @param Request $request HTTP request with optional search filter
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse View or JSON response
     */
    public function index(Request $request)
    {
        try {
            $query = Role::with('permissions')->withCount('users');

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $roles = $query->latest()->paginate(15)->withQueryString();
            $allPermissions = Permission::query()->orderBy('name')->get();
            $permissions = $this->groupPermissionsByModule($allPermissions);
            $hierarchicalPermissions = $this->groupPermissionsHierarchically($allPermissions);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'roles' => $roles,
                    'permissions' => $permissions,
                    'hierarchicalPermissions' => $hierarchicalPermissions,
                ]);
            }

            return view('dashboards.admin.pages.roles.index', compact('roles', 'permissions', 'hierarchicalPermissions'));
        } catch (\Exception $e) {
            Log::error('Role index error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load roles.');
        }
    }

    /**
     * Show the form for creating a new role
     *
     * Returns modal view with grouped permissions.
     *
     * @return \Illuminate\View\View Form view
     */
    public function create()
    {
        $permissions = $this->groupPermissionsByModule(Permission::query()->orderBy('name')->get());

        return view('dashboards.admin.pages.user.roles.partials.create-modal', compact('permissions'));
    }

    /**
     * Store a new role
     *
     * Creates a new role with assigned permissions.
     *
     * @param RoleRequest $request Validated request with role data
     * @return \Illuminate\Http\JsonResponse JSON response with success/error message
     */
    public function store(RoleRequest $request)
    {
        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web',
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully.',
                'data' => $role->load('permissions'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Role store error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create role: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display a single role
     *
     * Shows detailed view with assigned permissions and users.
     *
     * @param int|string $id Role ID
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse View or error response
     */
    public function show($id)
    {
        try {
            $role = Role::with(['permissions', 'users'])->findOrFail($id);
            $allPermissions = Permission::query()->orderBy('name')->get();
            $groupedPermissions = $this->groupPermissionsByModule($allPermissions);

            return view('dashboards.admin.pages.user.roles.partials.view', compact('role', 'groupedPermissions', 'allPermissions'));
        } catch (\Exception $e) {
            Log::error('Role show error: ' . $e->getMessage());
            return response()->json(['error' => 'Role not found.'], 404);
        }
    }

    /**
     * Get role data for editing
     *
     * Retrieves role data with permissions for form population via AJAX.
     *
     * @param int|string $id Role ID
     * @return \Illuminate\Http\JsonResponse JSON response with role data or error
     */
    public function edit($id)
    {
        try {
            $role = Role::with('permissions')->findOrFail($id);
            $permissions = $this->groupPermissionsByModule(Permission::query()->orderBy('name')->get());

            return response()->json([
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name'),
                'all_permissions' => $permissions,
            ]);
        } catch (\Exception $e) {
            Log::error('Role edit error: ' . $e->getMessage());
            return response()->json(['error' => 'Role not found.'], 404);
        }
    }

    /**
     * Group permissions by module
     *
     * Groups permissions by their module name extracted from permission name.
     *
     * @param Collection $permissions Collection of permissions
     * @return Collection Grouped permissions by module
     */
    private function groupPermissionsByModule(Collection $permissions): Collection
    {
        return $permissions->groupBy(function (Permission $permission) {
            return $this->extractPermissionModule($permission->name);
        })->sortKeys();
    }

    /**
     * Group permissions hierarchically by main modules and submodules
     *
     * Creates a hierarchical structure of permissions organized by:
     * - Main modules (Dashboard, Reports, Accounting, etc.)
     * - Submodules within each main module
     * - Permissions within each submodule
     *
     * @param Collection $permissions Collection of all permissions
     * @return array Hierarchical permission structure
     */
    private function groupPermissionsHierarchically(Collection $permissions): array
    {
        // Define main modules and their sub-modules
        $moduleStructure = [
            'Dashboard' => [
                'icon' => 'ti-dashboard',
                'submodules' => ['dashboard'],
            ],
            'Reports' => [
                'icon' => 'ti-chart-bar',
                'submodules' => ['reports', 'financial-reports', 'hr-reports', 'sales-reports', 'inventory-reports'],
            ],
            'Accounting' => [
                'icon' => 'ti-calculator',
                'submodules' => ['payment-methods', 'accounts', 'income-sources', 'expense-sources', 'income-transactions', 'expense-transactions', 'account-transfers', 'journal'],
            ],
            'HRMS' => [
                'icon' => 'ti-users-group',
                'submodules' => [
                    'HR Management' => ['departments', 'hr-jobs', 'employees','warnings', 'terminations', 'custodies','attendance-records', 'piece-production'],
                    'Leave Management' => ['leave-types', 'leave-balances', 'leave-requests', 'public-holidays'],
                    'Payroll' => ['payroll-cycles', 'salaries', 'payslips'],
                ],
            ],
            'Clients' => [
                'icon' => 'ti-building-store',
                'submodules' => ['client-categories', 'clients', 'patterns', 'client-profiles'],
            ],
            'Products' => [
                'icon' => 'ti-box',
                'submodules' => [
                    'Materials' => ['material-types', 'material-patterns', 'materials'],
                    'Components' => ['buttons', 'padding', 'lining'],
                    'Items' => ['items', 'additional-items'],
                ],
            ],
            'Orders' => [
                'icon' => 'ti-shopping-cart',
                // 'submodules' => ['orders', 'order-items', 'order-status-history', 'invoices', 'payments'],
                'submodules' => ['orders', 'invoices', 'payments'],
            ],
            'Suppliers' => [
                'icon' => 'ti-truck-delivery',
                'submodules' => ['suppliers', 'supplier-products', 'purchase-orders', 'purchase-order-items', 'supplier-payments'],
            ],
            'User Management' => [
                'icon' => 'ti-user-cog',
                'submodules' => ['users', 'roles'],
            ],
            'Employee Portal' => [
                'icon' => 'ti-user-circle',
                'submodules' => ['employee-portal'],
            ],
            'Website' => [
                'icon' => 'ti-web',
                'submodules' => ['website-home','website-about','website-contact', 'website-gallery', 'website-videos','inquiries'],
            ],

            'Settings' => [
                'icon' => 'ti-settings',
                'submodules' => ['settings'],
            ],
        ];

        // Group permissions by their module
        $groupedByModule = $permissions->groupBy(function (Permission $permission) {
            return $this->extractPermissionModule($permission->name);
        });

        $result = [];

        foreach ($moduleStructure as $mainModule => $config) {
            $mainModuleData = [
                'icon' => $config['icon'],
                'submodules' => [],
                'permissions' => [],
                'total_count' => 0,
            ];

            $submodules = $config['submodules'];

            // Check if submodules are nested (has sub-categories)
            $hasNestedSubmodules = false;
            foreach ($submodules as $key => $value) {
                if (is_string($key) && is_array($value)) {
                    $hasNestedSubmodules = true;
                    break;
                }
            }

            if ($hasNestedSubmodules) {
                // Handle nested submodules (like HRMS with HR Management, Attendance, etc.)
                foreach ($submodules as $subCategory => $modules) {
                    if (is_string($subCategory) && is_array($modules)) {
                        $subCategoryData = [
                            'modules' => [],
                            'total_count' => 0,
                        ];

                        foreach ($modules as $module) {
                            if ($groupedByModule->has($module)) {
                                $modulePermissions = $groupedByModule->get($module);
                                $subCategoryData['modules'][$module] = $modulePermissions;
                                $subCategoryData['total_count'] += $modulePermissions->count();
                                $mainModuleData['total_count'] += $modulePermissions->count();
                            }
                        }

                        if ($subCategoryData['total_count'] > 0) {
                            $mainModuleData['submodules'][$subCategory] = $subCategoryData;
                        }
                    }
                }
            } else {
                // Handle flat submodules (like Dashboard, Clients, etc.)
                foreach ($submodules as $module) {
                    if ($groupedByModule->has($module)) {
                        $modulePermissions = $groupedByModule->get($module);
                        $mainModuleData['permissions'][$module] = $modulePermissions;
                        $mainModuleData['total_count'] += $modulePermissions->count();
                    }
                }
            }

            if ($mainModuleData['total_count'] > 0) {
                $result[$mainModule] = $mainModuleData;
            }
        }

        return $result;
    }

    /**
     * Extract module name from permission name
     *
     * Extracts the module/resource name from a permission name by:
     * - Checking override mappings for special cases
     * - Removing action prefixes (create-, view-, edit-, etc.)
     * - Extracting the resource name after the prefix
     *
     * @param string $permissionName Permission name (e.g., 'create-users', 'view-reports')
     * @return string Module name (e.g., 'users', 'reports')
     */
    private function extractPermissionModule(string $permissionName): string
    {
        $overrides = [
            // Employee portal permissions (all should be grouped under employee-portal)
            'access-employee-portal' => 'employee-portal',
            'access-daily-worker-portal' => 'employee-portal',
            'view-own-dashboard' => 'employee-portal',
            'view-own-attendance' => 'employee-portal',
            'check-in-self' => 'employee-portal',
            'check-out-self' => 'employee-portal',
            'view-own-leaves' => 'employee-portal',
            'create-own-leave-request' => 'employee-portal',
            'cancel-own-leave-request' => 'employee-portal',
            'view-own-payslips' => 'employee-portal',
            'download-own-payslips' => 'employee-portal',
            'view-own-custodies' => 'employee-portal',
            'view-own-warnings' => 'employee-portal',
            'view-own-production' => 'employee-portal',
            'create-own-production' => 'employee-portal',

            'view-account-balance' => 'accounts',
            'update-account-balance' => 'accounts',
            'check-in-employees' => 'attendance-records',
            'check-out-employees' => 'attendance-records',
            'view-employee-salary' => 'employees',
            'view-employee-attendance' => 'employees',
            'initialize-leave-balances' => 'leave-balances',
            'set-active-client-profile' => 'client-profiles',
            'create-profile-from-pattern' => 'client-profiles',
            'update-order-status' => 'orders',
            'update-order-item-status' => 'order-items',
            'print-invoices' => 'invoices',
            'view-payment-receipts' => 'payments',
            'view-supplier-payment-receipts' => 'supplier-payments',
            'return-custodies' => 'custodies',
            'view-journal' => 'journal',
            'bulk-download-journal-entries' => 'journal',
            'view-reports' => 'reports',
            'view-analytics' => 'reports',
        ];

        if (array_key_exists($permissionName, $overrides)) {
            return $overrides[$permissionName];
        }

        $prefixes = [
            'bulk-force-delete',
            'force-delete',
            'bulk-upload',
            'bulk-download',
            'bulk-delete',
            'bulk-restore',
            'assign-permissions',
            'assign-roles',
            'set-active',
            'create',
            'view',
            'edit',
            'delete',
            'restore',
            'update',
            'approve',
            'reject',
            'cancel',
            'calculate',
            'process',
            'finalize',
            'pay',
            'print',
            'receive',
            'return',
        ];

        foreach ($prefixes as $prefix) {
            $needle = $prefix . '-';
            if (str_starts_with($permissionName, $needle)) {
                return substr($permissionName, strlen($needle)) ?: 'general';
            }
        }

        if (str_contains($permissionName, '-')) {
            return substr($permissionName, strpos($permissionName, '-') + 1) ?: 'general';
        }

        return 'general';
    }

    /**
     * Update an existing role
     *
     * Updates role name and syncs permissions.
     * Prevents renaming system roles (super-admin, admin).
     *
     * @param RoleRequest $request Validated request with role data
     * @param int|string $id Role ID to update
     * @return \Illuminate\Http\JsonResponse JSON response with success/error message
     */
    public function update(RoleRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $role = Role::findOrFail($id);

            if (in_array($role->name, ['super-admin', 'admin']) && $role->name !== $request->name) {
                return response()->json(['success' => false, 'message' => 'Cannot rename system roles.'], 403);
            }

            $role->update(['name' => $request->name]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully.',
                'data' => $role->load('permissions'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Role update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update role.'], 500);
        }
    }

    /**
     * Delete a role
     *
     * Soft deletes a role.
     * Prevents deletion of system roles and roles with assigned users.
     *
     * @param int|string $id Role ID to delete
     * @return \Illuminate\Http\JsonResponse JSON response with success/error message
     */
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);

            if (in_array($role->name, ['super-admin', 'admin'])) {
                return response()->json(['success' => false, 'message' => 'Cannot delete system roles.'], 403);
            }

            if ($role->users()->count() > 0) {
                return response()->json(['success' => false, 'message' => 'Cannot delete role with assigned users.'], 403);
            }

            DB::beginTransaction();
            $role->delete();
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Role deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Role destroy error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete role.'], 500);
        }
    }

    /**
     * Bulk delete roles
     *
     * Deletes multiple roles excluding system roles and roles with users.
     *
     * @param BulkActionRequest $request Validated request with role IDs
     * @return \Illuminate\Http\JsonResponse JSON response with success/error message
     */
    public function bulkDelete(BulkActionRequest $request)
    {
        try {
            $protectedRoles = Role::whereIn('name', ['super-admin', 'admin'])->pluck('id')->toArray();
            $ids = array_diff($request->ids, $protectedRoles);

            if (empty($ids)) {
                return response()->json(['success' => false, 'message' => 'No valid roles to delete.'], 400);
            }

            $rolesWithUsers = Role::whereIn('id', $ids)->whereHas('users')->pluck('id')->toArray();
            $ids = array_diff($ids, $rolesWithUsers);

            if (empty($ids)) {
                return response()->json(['success' => false, 'message' => 'Selected roles have assigned users.'], 400);
            }

            DB::beginTransaction();
            Role::whereIn('id', $ids)->delete();
            DB::commit();

            return response()->json(['success' => true, 'message' => count($ids) . ' role(s) deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Role bulkDelete error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete roles.'], 500);
        }
    }

    /**
     * Sync permissions for a role
     *
     * Updates the permissions assigned to a role.
     *
     * @param Request $request HTTP request with permissions array
     * @param int|string $id Role ID
     * @return \Illuminate\Http\JsonResponse JSON response with success/error message
     */
    public function syncPermissions(Request $request, $id)
    {
        try {
            $request->validate(['permissions' => 'array']);

            DB::beginTransaction();
            $role = Role::findOrFail($id);
            $role->syncPermissions($request->permissions ?? []);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permissions synced successfully.',
                'permissions' => $role->permissions->pluck('name'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Role syncPermissions error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to sync permissions.'], 500);
        }
    }

    /**
     * Export roles to Excel
     *
     * Exports all roles with user count and assigned permissions.
     *
     * @param Request $request HTTP request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse Excel download or redirect on error
     */
    public function bulkDownload(Request $request)
    {
        try {
            $roles = Role::with('permissions')->withCount('users')->get()->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'users_count' => $role->users_count,
                    'permissions' => $role->permissions->pluck('name')->implode(', '),
                    'created_at' => $role->created_at?->format('Y-m-d H:i:s'),
                ];
            });

            return Excel::download(
                new \App\Exports\GenericExport($roles->toArray(), array_keys($roles->first() ?? [])),
                'roles_' . now()->format('Y-m-d_His') . '.xlsx'
            );
        } catch (\Exception $e) {
            Log::error('Role bulkDownload error: ' . $e->getMessage());
            return back()->with('error', 'Failed to download roles.');
        }
    }
}
