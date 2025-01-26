<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create the roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // Role ID (Primary Key)
            $table->string('designation'); // Role name (Admin, Director, etc.)
            $table->timestamps(); // Created at and Updated at timestamps
        });

        // Insert default roles into the roles table
        DB::table('roles')->insert([
            ['designation' => 'Admin'],
            ['designation' => 'Director'],
            ['designation' => 'Inventory Manager'],
            ['designation' => 'Inventory Entry'],
            ['designation' => 'Sales'],
            ['designation' => 'Visitor'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles'); // Drop roles if this migration is rolled back
    }
}
