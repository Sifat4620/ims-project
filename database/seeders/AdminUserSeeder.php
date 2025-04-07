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
        DB::table('users')->insert([
            'full_name' => 'Fahim Faisal Sifat',
            'user_id' => '70',
            'email' => 'admin@sil.com',
            'phone' => null,
            'department' => 'Administration',
            'designation' => '1', // Assuming '1' corresponds to the admin designation
            'description' => 'System Administrator',
            'password' => Hash::make('123456'), // Use a hashed password
            'image' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
