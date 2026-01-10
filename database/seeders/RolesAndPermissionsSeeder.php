<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        // System
        Permission::firstOrCreate(['name' => 'manage_system']);
        Permission::firstOrCreate(['name' => 'manage_users']);
        Permission::firstOrCreate(['name' => 'manage_wizard']);
        
        // Libraries
        $editSystemLibs = Permission::firstOrCreate(['name' => 'edit_system_libraries']);
        $viewSystemLibs = Permission::firstOrCreate(['name' => 'view_system_libraries']);
        
        // User Data
        $manageAllData = Permission::firstOrCreate(['name' => 'manage_all_data']);
        $viewAllData = Permission::firstOrCreate(['name' => 'view_all_data']);
        $manageOwnData = Permission::firstOrCreate(['name' => 'manage_own_data']);
        
        // Features
        $useGenFeatures = Permission::firstOrCreate(['name' => 'use_generative_features']);

        // create roles and assign created permissions

        // User
        $userRole = Role::firstOrCreate(['name' => 'User']);
        $userRole->givePermissionTo([$viewSystemLibs, $manageOwnData, $useGenFeatures]);

        // Manager
        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        $managerRole->givePermissionTo([$editSystemLibs, $viewAllData, $manageOwnData, $useGenFeatures]);

        // Admin
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());
    }
}
