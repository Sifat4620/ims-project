<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Array of roles to be inserted
        $roles = [
            ['designation' => 'Admin'],
            ['designation' => 'Director'],
            ['designation' => 'Inventory Manager'],
            ['designation' => 'Inventory Entry'],
            ['designation' => 'Sales'],
            ['designation' => 'Visitor'],
        ];

        // Loop through the roles and insert them if they don't exist
        foreach ($roles as $role) {
            // Check if the role already exists
            $existingRole = DB::table('roles')->where('designation', $role['designation'])->first();

            // If the role doesn't exist, insert it
            if (!$existingRole) {
                DB::table('roles')->insert([
                    'designation' => $role['designation'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
