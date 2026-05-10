<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade as Bouncer;
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
        $roles = [
            ['name' => 'Admin', 'title' => 'Admin', 'scope' => 1],
            ['name' => 'Director', 'title' => 'Director', 'scope' => 2],
            ['name' => 'Inventory Manager', 'title' => 'Inventory Manager', 'scope' => 2],
            ['name' => 'Inventory Entry', 'title' => 'Inventory Entry', 'scope' => 2],
            ['name' => 'Sales', 'title' => 'Sales', 'scope' => 2],
            ['name' => 'Visitor', 'title' => 'Visitor', 'scope' => 2],
            ['name' => 'Solution Team Member',   'title' => 'Solution',   'scope' => 2],
        ];
        $bouncer = app(Bouncer::class);

         foreach ($roles as $role) {
            $exists = DB::table('roles')->where('name', $role['name'])->exists();

            if (!$exists) {
                DB::table('roles')->insert([
                    'name'       => $role['name'],
                    'title'      => $role['title'],
                    'scope'      => $role['scope'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
       }
    }

}