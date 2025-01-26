<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary key for the table
            $table->string('full_name'); // Full name of the user
            $table->string('user_id')->unique(); // employee user ID
            $table->string('email')->unique(); // User email
            $table->string('phone', 15)->nullable(); // Phone number (nullable in controller)
            $table->string('department'); // Department (required in controller)
            $table->string('designation'); // This value cames from roles table... 
            $table->text('description')->nullable(); // Description (nullable in controller)
            $table->string('password', 60); // Password (hashed) (bcrypt limit)
            $table->string('image')->nullable(); // Image column (nullable)
            $table->timestamps(); // Automatically adds 'created_at' and 'updated_at'
        });

        // Automatically create an admin user
        DB::table('users')->insert([
            'full_name' => 'Fahim Faisal Sifat',
            'user_id' => '70',
            'email' => 'admin@sil.com',
            'phone' => null,
            'department' => 'Administration',
            'designation' => '1',
            'description' => 'System Administrator',
            'password' => Hash::make('123456'), // Use a hashed password
            'image' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
