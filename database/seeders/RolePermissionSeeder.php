<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view-users',
            'show-users',
            'create-users',
            'edit-users',
            'download-users',
            'delete-users',
            'view-roles',
            'show-roles',
            'create-roles',
            'edit-roles',
            'download-roles',
            'delete-roles',
            'view-permissions',
            'show-permissions',
            'create-permissions',
            'edit-permissions',
            'download-permissions',
            'delete-permissions',

            //work-spaces permissions
            'view-work-spaces',
            'show-work-spaces',
            'create-work-spaces',
            'edit-work-spaces',
            'download-work-spaces',
            'delete-work-spaces',

            //work-space-members permissions
            'view-work-space-members',
            'show-work-space-members',
            'create-work-space-members',
            'edit-work-space-members',
            'download-work-space-members',
            'delete-work-space-members',

            'view-finance-transactions',
            'create-finance-transactions',
            'edit-finance-transactions',
            'delete-finance-transactions',
            'download-finance-transactions',
            'show-finance-transactions',

            'view-finance-merchants',
            'show-finance-merchants',
            'create-finance-merchants',
            'edit-finance-merchants',
            'delete-finance-merchants',
            'download-finance-merchants',

            'view-finance-categories',
            'show-finance-categories',
            'create-finance-categories',
            'edit-finance-categories',
            'delete-finance-categories',
            'download-finance-categories',

            // calendar permissions
            'view-calendar-events',
            'show-calendar-events',
            'create-calendar-events',
            'edit-calendar-events',
            'delete-calendar-events',
            'download-calendar-events',

            'view-calendar-review',
            'show-calendar-review',
            'create-calendar-review',
            'edit-calendar-review',
            'delete-calendar-review',
            'download-calendar-review',

            'view-calendar-tasks',
            'show-calendar-tasks',
            'create-calendar-tasks',
            'edit-calendar-tasks',
            'delete-calendar-tasks',
            'download-calendar-tasks',

            'view-calendar-dashboard',

        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        $superAdminRole->permissions()->sync(Permission::all());
        $adminRole->permissions()->sync(Permission::all());

        $editorRole->permissions()->sync(
            Permission::whereIn('name', [
                'view-users', 'show-users',
                'view-roles', 'show-roles',
                'view-permissions', 'show-permissions'
            ])->pluck('id')
        );

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        $superAdmin->roles()->sync([$superAdminRole->id]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        $admin->roles()->sync([$adminRole->id]);

        $editor = User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor User',
                'password' => Hash::make('password'),
            ]
        );

        $editor->roles()->sync([$editorRole->id]);

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
            ]
        );

        $user->roles()->sync([$userRole->id]);
    }
}
