<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\Team;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class SetupSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $team = Team::create(['name' => 'My Team']);

        $role_owner = Role::create(['name' => 'Owner']);
        $role_admin = Role::create(['name' => 'Admin']);
        $role_sales = Role::create(['name' => 'Sales']);
        $role_service = Role::create(['name' => 'Service']);

        $permission_read = Permission::create(['name' => 'read']);
        $permission_edit = Permission::create(['name' => 'edit']);
        $permission_write = Permission::create(['name' => 'write']);
        $permission_delete = Permission::create(['name' => 'delete']);
        
        $permissions_admin = [$permission_read, $permission_edit, $permission_write, $permission_delete];
        $role_owner->syncPermissions($permissions_admin);
        $role_admin->syncPermissions($permissions_admin);

        $users = [
            [
                'name' => 'Owner',
                'email' => 'owner@gmail.com',
                'password' => 'password',
                'role' => [$role_owner],
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => 'password',
                'role' => [$role_admin],
            ],
            [
                'name' => 'Sales',
                'email' => 'sales@gmail.com',
                'password' => 'password',
                'role' => [$role_sales],
            ]
        ];

        foreach($users as $user) {
            $created_user = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
            ]);

            $created_user->syncRoles($user['role'], $team);
        }
    }
}
