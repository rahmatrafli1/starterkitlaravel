<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'owner', 'karyawan'];
        $statuses = ['active', 'inactive'];

        // Buat admin utama
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'nomor_telepon' => '08123456789',
            'status' => 'active',
        ]);
        $admin->assignRole('admin');

        // Buat 49 user lainnya
        for ($i = 1; $i <= 49; $i++) {
            $user = User::create([
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
                'password' => Hash::make('password'),
                'nomor_telepon' => '0812' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'status' => $statuses[array_rand($statuses)],
            ]);

            // Assign role secara acak
            $role = $roles[array_rand($roles)];
            $user->assignRole($role);
        }
    }
}
