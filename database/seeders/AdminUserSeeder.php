<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Silber\Bouncer\BouncerFacade as Bouncer;
use App\Models\User;

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
        $user = User::create([
            'full_name' => 'Fahim Faisal Sifat',
            'user_id' => '70',
            'email' => 'admin@sil.com',
            'phone' => null,
            'department' => 'Administration',
            'designation' => '1', 
            'description' => 'System Administrator',
            'password' => Hash::make('123456'), 
            'image' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Ensure the 'Admin' role exists using Bouncer
        $role = Bouncer::role()->firstOrCreate(['name' => 'Admin', 'title' => 'Admin']);

        // Assign the 'Admin' role to the user using Bouncer
        $user->assign($role);
    }
}
