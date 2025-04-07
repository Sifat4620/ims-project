<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Call the AdminUserSeeder or any other seeders
        $this->call(AdminUserSeeder::class);  
    }
}
