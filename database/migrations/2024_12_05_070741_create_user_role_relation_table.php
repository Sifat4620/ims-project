<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRoleRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create user_role_pages_relation table
        Schema::create('user_role_pages_relation', function (Blueprint $table) {
            $table->id('relation_id'); // Primary Key for the relation table

            // Foreign key to roles table
            $table->foreignId('role_id')
                ->constrained('roles')  // Foreign key reference to roles table
                ->onDelete('cascade');  // Cascade delete if the role is deleted

            // Foreign key to pages table
            $table->foreignId('page_id')
                ->constrained('pages')  // Foreign key reference to pages table
                ->onDelete('cascade');  // Cascade delete if the page is deleted

            $table->timestamps(); // Created at and Updated at timestamps
        });

        // Insert the role-page access for each role
        // Admin (Role ID 1) and Director (Role ID 2) can see all pages
        $adminRoleId = 1;
        $directorRoleId = 2;

        // Assuming you have 25 pages, you can give these roles access to all pages
        foreach (range(1, 25) as $pageId) {
            DB::table('user_role_pages_relation')->insert([
                'role_id' => $adminRoleId,
                'page_id' => $pageId,
            ]);
            DB::table('user_role_pages_relation')->insert([
                'role_id' => $directorRoleId,
                'page_id' => $pageId,
            ]);
        }

        // Inventory Manager (Role ID 3): pages 1-7, 13-16, 20-25
        $inventoryManagerRoleId = 3;
        foreach (array_merge(range(1, 7), range(13, 16), range(20, 25)) as $pageId) {
            DB::table('user_role_pages_relation')->insert([
                'role_id' => $inventoryManagerRoleId,
                'page_id' => $pageId,
            ]);
        }

        // Inventory Entry (Role ID 4): pages 1-7, 12, 8-10, 16, 17
        $inventoryEntryRoleId = 4;
        foreach (array_merge(range(1, 7), [12], range(8, 10), [16, 17]) as $pageId) {
            DB::table('user_role_pages_relation')->insert([
                'role_id' => $inventoryEntryRoleId,
                'page_id' => $pageId,
            ]);
        }

        // Sales (Role ID 5): pages 1-7, 20-24
        $salesRoleId = 5;
        foreach (array_merge(range(1, 7), range(20, 24)) as $pageId) {
            DB::table('user_role_pages_relation')->insert([
                'role_id' => $salesRoleId,
                'page_id' => $pageId,
            ]);
        }

        // Visitor (Role ID 6): Assuming they can only see limited pages like "Invoice List" (page_id = 25)
        $visitorRoleId = 6;
        DB::table('user_role_pages_relation')->insert([
            'role_id' => $visitorRoleId,
            'page_id' => 25, // Example page that the visitor can see
        ]);
    }

    public function down()
    {
        // Drop the relation table if this migration is rolled back
        Schema::dropIfExists('user_role_pages_relation');
    }

}
