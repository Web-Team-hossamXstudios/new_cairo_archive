<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        try {
            DB::beginTransaction();

            // Reset cached roles and permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // Define permissions by module
            $permissions = [
                // Client Management
                'clients.view' => 'عرض العملاء',
                'clients.create' => 'إضافة عميل',
                'clients.edit' => 'تعديل عميل',
                'clients.delete' => 'حذف عميل',
                'clients.export' => 'تصدير العملاء',

                // Land Management
                'lands.view' => 'عرض القطع',
                'lands.create' => 'إضافة قطعه',
                'lands.edit' => 'تعديل قطعه',
                'lands.delete' => 'حذف قطعه',

                // File Management
                'files.view' => 'عرض الملفات',
                'files.upload' => 'رفع ملفات',
                'files.delete' => 'حذف ملفات',
                'files.download' => 'تحميل ملفات',

                // Physical Locations
                'physical_locations.view' => 'عرض مواقع التخزين',
                'physical_locations.manage' => 'إدارة مواقع التخزين',

                // Geographic Areas
                'geographic_areas.view' => 'عرض المناطق الجغرافية',
                'geographic_areas.manage' => 'إدارة المناطق الجغرافية',
                'geographic-areas.view' => 'عرض المناطق الجغرافية',
                'geographic-areas.manage' => 'إدارة المناطق الجغرافية',

                // Items (Content Types)
                'items.view' => 'عرض أنواع المحتوى',
                'items.manage' => 'إدارة أنواع المحتوى',

                // Import
                'import.access' => 'الوصول للاستيراد',
                'import.execute' => 'تنفيذ الاستيراد',

                // User Management
                'users.view' => 'عرض المستخدمين',
                'users.create' => 'إضافة مستخدم',
                'users.edit' => 'تعديل مستخدم',
                'users.delete' => 'حذف مستخدم',
                'users.restore' => 'استعادة مستخدم',
                'users.force-delete' => 'حذف نهائي للمستخدم',
                'users.bulk-upload' => 'استيراد مستخدمين',
                'users.bulk-download' => 'تصدير مستخدمين',
                'users.bulk-delete' => 'حذف مجموعة مستخدمين',
                'users.bulk-restore' => 'استعادة مجموعة مستخدمين',
                'users.bulk-force-delete' => 'حذف نهائي لمجموعة مستخدمين',

                // Roles & Permissions
                'roles.view' => 'عرض الأدوار',
                'roles.create' => 'إضافة دور',
                'roles.edit' => 'تعديل دور',
                'roles.delete' => 'حذف دور',
                'roles.manage' => 'إدارة الأدوار',

                // Reports
                'reports.view' => 'عرض التقارير',
                'reports.export' => 'تصدير التقارير',
            ];

            // Create permissions
            foreach ($permissions as $name => $description) {
                Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'web']
                );
            }

            // Define roles with their permissions
            $roles = [
                'Super Admin' => array_keys($permissions), // All permissions

                'Manager' => [
                    'clients.view', 'clients.create', 'clients.edit', 'clients.delete', 'clients.export',
                    'lands.view', 'lands.create', 'lands.edit', 'lands.delete',
                    'files.view', 'files.upload', 'files.delete', 'files.download',
                    'physical_locations.view', 'physical_locations.manage',
                    'geographic_areas.view', 'geographic_areas.manage',
                    'geographic-areas.view', 'geographic-areas.manage',
                    'items.view', 'items.manage',
                    'import.access', 'import.execute',
                    'users.view', 'users.create', 'users.edit', 'users.delete',
                    'roles.view',
                    'reports.view', 'reports.export',
                ],

                'Employee' => [
                    'clients.view', 'clients.create', 'clients.edit',
                    'lands.view', 'lands.create', 'lands.edit',
                    'files.view', 'files.upload', 'files.download',
                    'physical_locations.view',
                    'geographic_areas.view',
                    'geographic-areas.view',
                    'items.view',
                    'import.access',
                    'users.view',
                    'reports.view',
                ],

                'Viewer' => [
                    'clients.view',
                    // 'lands.view',
                    // 'files.view', 'files.download',
                    // 'physical_locations.view',
                    // 'geographic_areas.view',
                    // 'geographic-areas.view',
                    // 'items.view',
                    // 'reports.view',
                ],
            ];

            // Create roles and assign permissions
            foreach ($roles as $roleName => $rolePermissions) {
                $role = Role::firstOrCreate(
                    ['name' => $roleName, 'guard_name' => 'web']
                );
                $role->syncPermissions($rolePermissions);
            }

            DB::commit();

            $this->command->info('Permissions and roles seeded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PermissionSeeder failed: ' . $e->getMessage());
            $this->command->error('Failed to seed permissions: ' . $e->getMessage());
        }
    }
}
