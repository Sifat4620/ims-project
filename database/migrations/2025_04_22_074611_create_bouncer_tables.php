<?php

use Silber\Bouncer\Database\Models;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBouncerTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the tables already exist before creating them
        if (!Schema::hasTable(Models::table('abilities'))) {
            Schema::create(Models::table('abilities'), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('title')->nullable();
                $table->bigInteger('entity_id')->unsigned()->nullable();
                $table->string('entity_type')->nullable();
                $table->boolean('only_owned')->default(false);
                $table->json('options')->nullable();
                $table->integer('scope')->nullable()->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable(Models::table('roles'))) {
            Schema::create(Models::table('roles'), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('title')->nullable();
                $table->integer('scope')->nullable()->index();
                $table->timestamps();

                $table->unique(
                    ['name', 'scope'],
                    'roles_name_unique'
                );
            });
        }

        if (!Schema::hasTable(Models::table('assigned_roles'))) {
            Schema::create(Models::table('assigned_roles'), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('role_id')->unsigned()->index();
                $table->bigInteger('entity_id')->unsigned();
                $table->string('entity_type');
                $table->bigInteger('restricted_to_id')->unsigned()->nullable();
                $table->string('restricted_to_type')->nullable();
                $table->integer('scope')->nullable()->index();

                $table->index(
                    ['entity_id', 'entity_type', 'scope'],
                    'assigned_roles_entity_index'
                );

                $table->foreign('role_id')
                      ->references('id')->on(Models::table('roles'))
                      ->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable(Models::table('permissions'))) {
            Schema::create(Models::table('permissions'), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('ability_id')->unsigned()->index();
                $table->bigInteger('entity_id')->unsigned()->nullable();
                $table->string('entity_type')->nullable();
                $table->boolean('forbidden')->default(false);
                $table->integer('scope')->nullable()->index();

                $table->index(
                    ['entity_id', 'entity_type', 'scope'],
                    'permissions_entity_index'
                );

                $table->foreign('ability_id')
                      ->references('id')->on(Models::table('abilities'))
                      ->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the tables if they exist
        if (Schema::hasTable(Models::table('permissions'))) {
            Schema::dropIfExists(Models::table('permissions'));
        }
        if (Schema::hasTable(Models::table('assigned_roles'))) {
            Schema::dropIfExists(Models::table('assigned_roles'));
        }
        if (Schema::hasTable(Models::table('roles'))) {
            Schema::dropIfExists(Models::table('roles'));
        }
        if (Schema::hasTable(Models::table('abilities'))) {
            Schema::dropIfExists(Models::table('abilities'));
        }
    }
}
