<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('designation'); // This value comes from roles table... 
            $table->text('description')->nullable(); // Description (nullable in controller)
            $table->string('password', 60); // Password (hashed) (bcrypt limit)
            $table->string('image')->nullable(); // Image column (nullable)
            $table->timestamps(); // Automatically adds 'created_at' and 'updated_at'
        });
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
