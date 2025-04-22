<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveForeignKeyFromRoleUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remove foreign key constraint from the 'role_user' table if it exists
        Schema::table('role_user', function (Blueprint $table) {
            // Check if the foreign key exists and drop it
            $foreignKeyName = 'role_user_user_id_foreign'; // Specify the actual foreign key name here
            if (Schema::hasTable('role_user') && Schema::hasColumn('role_user', 'user_id')) {
                // If foreign key exists, drop it
                $table->dropForeign($foreignKeyName);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Add the foreign key back in case of rollback
        Schema::table('role_user', function (Blueprint $table) {
            if (Schema::hasTable('role_user') && Schema::hasColumn('role_user', 'user_id')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }
}
