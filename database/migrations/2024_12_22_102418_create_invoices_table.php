<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_number');
            $table->unsignedBigInteger('item_id'); // Link to the items table
            $table->string('customer_address');
            $table->string('authorized_name');
            $table->string('authorized_designation');
            $table->string('authorized_mobile');
            $table->string('recipient_name');
            $table->string('recipient_designation');
            $table->string('recipient_organization');
            $table->date('date_issued');  
            $table->string('po_no')->nullable(); 
            $table->date('po_date')->nullable();
            $table->timestamps();

            // Establish a foreign key relationship with the items table
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
