<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Call the RoleSeeder to insert roles
        $this->call(RoleSeeder::class);

        // Call the AdminUserSeeder to insert admin user
        $this->call(AdminUserSeeder::class);

        // Add any other seeders you want to call
        // $this->call(OtherSeeder::class);
    }
}
