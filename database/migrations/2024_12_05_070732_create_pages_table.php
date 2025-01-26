<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id(); // Page ID (Primary Key)
            $table->string('page_name'); // Name of the page (e.g., In Stock, Delivery Challan, etc.)
            $table->timestamps(); // Created at and Updated at timestamps
        });

        // Predefined pages
        DB::table('pages')->insert([
            ['page_name' => 'In Stock', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Delivery Challan', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Gate Pass', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Returnable', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Defective', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Warranty Log', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Core Data', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Invoice List', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Confirm', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Invoice Download', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Edit', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'In House', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Primary Records', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Pricing Details', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Product Specification', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Local File Upload', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Import File Upload', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Return Status Log', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Current Stock Levels', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'PO Stock Report', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'L/C Wise Stock', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Defective Items Report', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Product Warranty Overview', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Revenue Summary', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Users List', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Users Creation', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Role Assign', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'View Profile', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'User Activity', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'System Logs', 'created_at' => now(), 'updated_at' => now()],
            ['page_name' => 'Usage Statistics', 'created_at' => now(), 'updated_at' =>now()],
            ['page_name' => 'Currencies', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
