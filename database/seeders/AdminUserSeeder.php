<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert admin user
        $userId = DB::table('users')->insertGetId([
            'full_name' => 'Fahim Faisal Sifat',
            'user_id' => '70',
            'email' => 'admin@sil.com',
            'phone' => null,
            'department' => 'Administration',
            'designation' => '1', // Assuming '1' corresponds to the admin role
            'description' => 'System Administrator',
            'password' => Hash::make('123456'), // Use a hashed password
            'image' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Ensure the 'Admin' role exists with ID 1
        $adminRoleId = DB::table('roles')->where('designation', 'Admin')->value('id');

        // Assign the 'Admin' role to the user in the role_user pivot table
        if ($adminRoleId) {
            DB::table('role_user')->insert([
                'role_id' => $adminRoleId,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
