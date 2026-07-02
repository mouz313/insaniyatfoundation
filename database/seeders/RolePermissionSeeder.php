<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'donor-create', 'donor-edit', 'donor-delete', 'donor-show',
            'blood-donation-create', 'blood-donation-edit', 'blood-donation-delete', 'blood-donation-show',
            'inventory-create', 'inventory-edit', 'inventory-delete', 'inventory-show',
            'blood-request-create', 'blood-request-edit', 'blood-request-delete', 'blood-request-show',
            'blood-request-match',
            'call-log-create', 'call-log-edit', 'call-log-delete',
            'money-donation-create', 'money-donation-edit', 'money-donation-delete', 'money-donation-show',
            'campaign-create', 'campaign-edit', 'campaign-delete', 'campaign-show',
            'staff-create', 'staff-edit', 'staff-delete',
            'manage-settings',
            'view-reports',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->givePermissionTo(Permission::whereNotIn('name', [
            'staff-create', 'staff-edit', 'staff-delete', 'manage-settings',
        ])->get());

        $fieldStaff = Role::firstOrCreate(['name' => 'field_staff']);
        $fieldStaff->givePermissionTo([
            'donor-create', 'donor-edit', 'donor-show',
            'blood-donation-create', 'blood-donation-show',
            'blood-request-create', 'blood-request-show',
            'call-log-create', 'call-log-edit',
        ]);

        $dataEntry = Role::firstOrCreate(['name' => 'data_entry']);
        $dataEntry->givePermissionTo([
            'donor-create', 'donor-edit', 'donor-show',
            'blood-donation-create', 'blood-donation-edit', 'blood-donation-show',
            'blood-request-create', 'blood-request-edit', 'blood-request-show',
            'call-log-create', 'call-log-edit',
            'money-donation-create', 'money-donation-edit', 'money-donation-show',
            'campaign-show',
        ]);

        $reportViewer = Role::firstOrCreate(['name' => 'report_viewer']);
        $reportViewer->givePermissionTo(['view-reports']);
    }
}
