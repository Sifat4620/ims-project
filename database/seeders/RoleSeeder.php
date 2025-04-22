<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Bouncer; // Import the Bouncer facade

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
            ['name' => 'Admin', 'title' => 'Admin', 'scope' => 1],  
            ['name' => 'Director', 'title' => 'Director', 'scope' => 2],  
            ['name' => 'Inventory Manager', 'title' => 'Inventory Manager', 'scope' => 2],  
            ['name' => 'Inventory Entry', 'title' => 'Inventory Entry', 'scope' => 2],  
            ['name' => 'Sales', 'title' => 'Sales', 'scope' => 2],  
            ['name' => 'Visitor', 'title' => 'Visitor', 'scope' => 2],  
        ];

        // Loop through the roles and create them if they don't exist
        foreach ($roles as $role) {
            // Create or update the role in Bouncer
            Bouncer::role()->firstOrCreate([
                'name' => $role['name'],
                'title' => $role['title'],
            ], [
                'scope' => $role['scope'], // Include scope
            ]);
        }
    }
}
