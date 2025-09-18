<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat permissions
        $permissions = [
            'add-user',
            'edit-user',
            'delete-user',
            'view-user',
            'view-dashboard',
            'edit-profile'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Buat roles dan assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $ownerRole = Role::create(['name' => 'owner']);
        $karyawanRole = Role::create(['name' => 'karyawan']);

        // Admin mendapat semua permission
        $adminRole->givePermissionTo($permissions);

        // Owner mendapat permission terbatas
        $ownerRole->givePermissionTo([
            'view-user',
            'view-dashboard',
            'edit-profile'
        ]);

        // Karyawan mendapat permission terbatas
        $karyawanRole->givePermissionTo([
            'edit-user',
            'view-user',
            'view-dashboard',
            'edit-profile'
        ]);
    }
}
