<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BulkActionRequest;
use App\Http\Requests\Admin\BulkUploadRequest;
use App\Http\Requests\Admin\UserRequest;
use App\Jobs\BulkDeleteJob;
use App\Jobs\BulkRestoreJob;
use App\Jobs\BulkUploadJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;

/**
 * UserController
 *
 * Manages system users and employees.
 * Handles user CRUD operations, role assignment, and avatar management.
 * Supports bulk operations and Excel import/export.
 * Prevents users from deleting or deactivating their own account.
 *
 * @package App\Http\Controllers\Admin\Users
 */
class UserController extends Controller
{
    /**
     * Display a listing of users
     *
     * Shows paginated list with filtering by search, role, department, status, and employee type.
     * Includes soft-deleted users when trashed filter is applied.
     *
     * @param Request $request HTTP request with filter parameters
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse View or redirect on error
     */
    public function index(Request $request)
    {
        try {
            $query = User::with(['roles']);

            if ($request->input('trashed') === 'only') {
                $query->onlyTrashed();
            }

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%')
                      ->orWhere('first_name', 'like', '%' . $request->search . '%')
                      ->orWhere('last_name', 'like', '%' . $request->search . '%');
                });
            }

            if ($request->filled('role')) {
                $query->whereHas('roles', function ($q) use ($request) {
                    $q->where('name', $request->role);
                });
            }

            if ($request->filled('status')) {
                if ($request->status === 'active') {
                    $query->where('is_active', true);
                }

                if ($request->status === 'inactive') {
                    $query->where('is_active', false);
                }
            }

            if ($request->filled('employee_type')) {
                $query->where('employee_type', $request->employee_type);
            }

            $users = $query->latest()->paginate(15)->withQueryString();
            $roles = Role::all();

            return view('dashboards.admin.pages.users.index', compact('users', 'roles'));
        } catch (\Exception $e) {
            Log::error('User index error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load users.');
        }
    }

    /**
     * Show the form for creating a new user
     *
     * Returns modal view with roles, departments, and jobs.
     *
     * @return \Illuminate\View\View Form view
     */
    public function create()
    {
        $roles = Role::all();
        return view('dashboards.admin.pages.user.users.partials.create-modal', compact('roles'));
    }

    /**
     * Store a new user
     *
     * Creates a new user with hashed password and auto-generated employee code.
     * Assigns role and handles avatar upload using Spatie Media Library.
     *
     * @param UserRequest $request Validated request with user data
     * @return \Illuminate\Http\JsonResponse JSON response with success/error message
     */
    public function store(UserRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            $data['employee_code'] = User::generateEmployeeCode();

            $user = User::create($data);

            if ($request->filled('role')) {
                $user->syncRoles([$request->role]);
            }

            if ($request->hasFile('avatar')) {
                $user->addMediaFromRequest('avatar')
                    ->toMediaCollection('avatar');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'data' => $user->load('roles'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User store error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create user: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display a single user
     *
     * Shows detailed view including soft-deleted users.
     *
     * @param int|string $id User ID
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse View or error response
     */
    public function show($id)
    {
        try {
            $user = User::with(['roles'])
                ->withTrashed()
                ->findOrFail($id);
            return view('dashboards.admin.pages.user.users.partials.view', compact('user'));
        } catch (\Exception $e) {
            Log::error('User show error: ' . $e->getMessage());
            return response()->json(['error' => 'User not found.'], 404);
        }
    }

    /**
     * Get user data for editing
     *
     * Retrieves user data with roles, departments, and jobs for form population via AJAX.
     *
     * @param int|string $id User ID
     * @return \Illuminate\Http\JsonResponse JSON response with user data or error
     */
    public function edit($id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);
            $roles = Role::all();

            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'employee_code' => $user->employee_code,
                'employee_type' => $user->employee_type,
                'national_id' => $user->national_id,
                'phone' => $user->phone,
                'birth_date' => $user->birth_date?->format('Y-m-d'),
                'gender' => $user->gender,
                'address' => $user->address,
                'department_id' => $user->department_id,
                'hr_job_id' => $user->hr_job_id,
                'hire_date' => $user->hire_date?->format('Y-m-d'),
                'basic_salary' => $user->basic_salary,
                'piece_rate' => $user->piece_rate,
                'employment_status' => $user->employment_status,
                'is_active' => $user->is_active,
                'roles' => $user->roles->pluck('name'),
                'avatar_url' => $user->getFirstMediaUrl('avatar'),
            ]);
        } catch (\Exception $e) {
            Log::error('User edit error: ' . $e->getMessage());
            return response()->json(['error' => 'User not found.'], 404);
        }
    }

    /**
     * Update an existing user
     *
     * Updates user data with optional password change.
     * Syncs role and handles avatar upload/replacement.
     *
     * @param UserRequest $request Validated request with user data
     * @param int|string $id User ID to update
     * @return \Illuminate\Http\JsonResponse JSON response with success/error message
     */
    public function update(UserRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $data = $request->validated();

            if ($request->filled('password')) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            if ($request->filled('role')) {
                $user->syncRoles([$request->role]);
            }

            if ($request->hasFile('avatar')) {
                $user->clearMediaCollection('avatar');
                $user->addMediaFromRequest('avatar')
                    ->toMediaCollection('avatar');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'data' => $user->load('roles'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update user.'], 500);
        }
    }

    /**
     * Soft delete a user
     *
     * Marks user as deleted but keeps in database.
     * Prevents users from deleting their own account.
     *
     * @param int|string $id User ID to delete
     * @return \Illuminate\Http\JsonResponse JSON response with success/error message
     */
    public function destroy($id)
    {
        try {
            if ($id == auth()->id()) {
                return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 403);
            }

            DB::beginTransaction();
            User::findOrFail($id)->delete();
            DB::commit();

            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User destroy error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete user.'], 500);
        }
    }

    public function changePassword(Request $request, $id)
    {
        try {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);

            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->password = Hash::make($request->password);
            $user->save();

            DB::commit();

            Log::info('Password changed for user: ' . $user->name . ' by ' . auth()->user()->name);

            return response()->json(['success' => true, 'message' => 'Password changed successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Password change error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to change password.'], 500);
        }
    }

    /**
     * Bulk soft delete users
     *
     * Dispatches job to soft delete multiple users.
     * Excludes current user from deletion.
     *
     * @param BulkActionRequest $request Validated request with user IDs
     * @return \Illuminate\Http\JsonResponse JSON response with success/error message
     */
    public function bulkDelete(BulkActionRequest $request)
    {
        try {
            $ids = array_filter($request->ids, fn($id) => $id != auth()->id());
            if (empty($ids)) {
                return response()->json(['success' => false, 'message' => 'No valid users to delete.'], 400);
            }
            BulkDeleteJob::dispatch(User::class, $ids, false, auth()->id());
            return response()->json(['success' => true, 'message' => 'Bulk delete job queued.']);
        } catch (\Exception $e) {
            Log::error('User bulkDelete error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to queue bulk delete.'], 500);
        }
    }

    /**
     * Bulk restore soft-deleted users
     *
     * Dispatches job to restore multiple soft-deleted users.
     *
     * @param BulkActionRequest $request Validated request with user IDs
     * @return \Illuminate\Http\JsonResponse JSON response with success/error message
     */
    public function bulkRestore(BulkActionRequest $request)
    {
        try {
            BulkRestoreJob::dispatch(User::class, $request->ids, auth()->id());
            return response()->json(['success' => true, 'message' => 'Bulk restore job queued.']);
        } catch (\Exception $e) {
            Log::error('User bulkRestore error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to queue bulk restore.'], 500);
        }
    }

    /**
     * Bulk force delete users permanently
     *
     * Dispatches job to permanently delete multiple users.
     * Excludes current user from deletion.
     *
     * @param BulkActionRequest $request Validated request with user IDs
     * @return \Illuminate\Http\JsonResponse JSON response with success/error message
     */
    public function bulkForceDelete(BulkActionRequest $request)
    {
        try {
            $ids = array_filter($request->ids, fn($id) => $id != auth()->id());
            if (empty($ids)) {
                return response()->json(['success' => false, 'message' => 'No valid users to delete.'], 400);
            }
            BulkDeleteJob::dispatch(User::class, $ids, true, auth()->id());
            return response()->json(['success' => true, 'message' => 'Bulk force delete job queued.']);
        } catch (\Exception $e) {
            Log::error('User bulkForceDelete error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to queue bulk force delete.'], 500);
        }
    }

    /**
     * Restore a soft-deleted user
     *
     * Restores a soft-deleted user, making them active again.
     *
     * @param int|string $id User ID to restore
     * @return \Illuminate\Http\JsonResponse JSON response with success/error message
     */
    public function restore($id)
    {
        try {
            DB::beginTransaction();
            User::withTrashed()->findOrFail($id)->restore();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'User restored successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User restore error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to restore user.'], 500);
        }
    }

    /**
     * Permanently delete a user
     *
     * Removes user and avatar from database permanently.
     * Prevents users from deleting their own account.
     *
     * @param int|string $id User ID to permanently delete
     * @return \Illuminate\Http\JsonResponse JSON response with success/error message
     */
    public function forceDelete($id)
    {
        try {
            if ($id == auth()->id()) {
                return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 403);
            }

            DB::beginTransaction();
            $user = User::withTrashed()->findOrFail($id);
            $user->clearMediaCollection('avatar');
            $user->forceDelete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'User permanently deleted.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User forceDelete error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to permanently delete user.'], 500);
        }
    }

    /**
     * Bulk upload users from Excel
     *
     * Dispatches job to import users from Excel file.
     * Supports create and upsert modes.
     *
     * @param BulkUploadRequest $request Validated request with Excel file
     * @return \Illuminate\Http\JsonResponse JSON response with success/error message
     */
    public function bulkUpload(BulkUploadRequest $request)
    {
        try {
            $file = $request->file('file');
            $mode = $request->input('mode', 'create');

            $path = $file->store('bulk-uploads', 'local');

            $mapping = [
                'name' => 'name',
                'email' => 'email',
                'password' => 'password',
                'first_name' => 'first_name',
                'last_name' => 'last_name',
                'phone' => 'phone',
                'role' => 'role',
                'department_id' => 'department_id',
                'is_active' => 'is_active',
            ];

            $requiredFields = ['name', 'email', 'password'];
            $defaults = [
                'is_active' => 1,
            ];

            BulkUploadJob::dispatch(
                $path,
                User::class,
                $mode,
                $mapping,
                auth()->id(),
                $requiredFields,
                $defaults
            );

            return response()->json(['success' => true, 'message' => 'Bulk upload job queued.']);
        } catch (\Exception $e) {
            Log::error('User bulkUpload error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to queue bulk upload.'], 500);
        }
    }

    /**
     * Export users to Excel
     *
     * Exports all users with roles, departments, and status.
     *
     * @param Request $request HTTP request with optional trashed filter
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse Excel download or redirect on error
     */
    public function bulkDownload(Request $request)
    {
        try {
            $query = User::with('roles');

            if ($request->has('trashed') && $request->trashed === 'only') {
                $query->onlyTrashed();
            }

            $users = $query->get()->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'employee_code' => $user->employee_code,
                    'phone' => $user->phone,
                    'role' => $user->roles->pluck('name')->implode(', '),
                    'department' => $user->department?->name,
                    'is_active' => $user->is_active ? 'Yes' : 'No',
                    'created_at' => $user->created_at?->format('Y-m-d H:i:s'),
                ];
            });

            return Excel::download(
                new \App\Exports\GenericExport($users->toArray(), array_keys($users->first() ?? [])),
                'users_' . now()->format('Y-m-d_His') . '.xlsx'
            );
        } catch (\Exception $e) {
            Log::error('User bulkDownload error: ' . $e->getMessage());
            return back()->with('error', 'Failed to download users.');
        }
    }

    /**
     * Download sample Excel file for bulk upload
     *
     * Provides a sample Excel file with example user data.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse Excel download
     */
    public function downloadSample()
    {
        $sampleData = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'password123',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'phone' => '01234567890',
                'role' => 'admin',
                'is_active' => '1',
            ],
        ];

        return Excel::download(
            new \App\Exports\GenericExport($sampleData, array_keys($sampleData[0])),
            'users_sample.xlsx'
        );
    }

    /**
     * Toggle user active status
     *
     * Activates or deactivates a user.
     * Prevents users from deactivating their own account.
     *
     * @param int|string $id User ID
     * @return \Illuminate\Http\JsonResponse JSON response with success/error message
     */
    public function toggleStatus($id)
    {
        try {
            if ($id == auth()->id()) {
                return response()->json(['success' => false, 'message' => 'You cannot deactivate your own account.'], 403);
            }

            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->update(['is_active' => !$user->is_active]);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User status updated successfully.',
                'is_active' => $user->is_active,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User toggleStatus error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update user status.'], 500);
        }
    }

    /**
     * Assign role to user
     *
     * Syncs a single role to the user.
     *
     * @param Request $request HTTP request with role name
     * @param int|string $id User ID
     * @return \Illuminate\Http\JsonResponse JSON response with success/error message
     */
    public function assignRole(Request $request, $id)
    {
        try {
            $request->validate(['role' => 'required|exists:roles,name']);

            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->syncRoles([$request->role]);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Role assigned successfully.',
                'roles' => $user->roles->pluck('name'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User assignRole error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to assign role.'], 500);
        }
    }
}
