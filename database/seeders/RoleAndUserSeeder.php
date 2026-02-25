<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $petugasRole = Role::firstOrCreate(['name' => 'petugas']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@minangmart.com'],
            [
                'name' => 'Admin Minangmart',
                'username' => 'admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole($adminRole);

        // Create Petugas
        $petugas = User::firstOrCreate(
            ['email' => 'petugas@minangmart.com'],
            [
                'name' => 'Petugas Minangmart',
                'username' => 'petugas',
                'password' => Hash::make('password'),
            ]
        );
        $petugas->assignRole($petugasRole);

        // Create Customer
        $customer = User::firstOrCreate(
            ['email' => 'customer@minangmart.com'],
            [
                'name' => 'Customer Minangmart',
                'username' => 'customer',
                'password' => Hash::make('password'),
            ]
        );
        $customer->assignRole($customerRole);
    }
}
