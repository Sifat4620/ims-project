<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('item_id')->index(); // Foreign key for items table
            $table->text('description')->nullable(); // Description for additional details
            $table->string('status', 50); // Status of the return (e.g., Return, Faulty)
            $table->string('lc_po_type')->nullable(); // Purchase order type
            $table->timestamps(); // created_at and updated_at

            // Foreign key constraint
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('returns');
    }
};
