<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UploadDocument extends Migration
{
    public function up()
    {
        Schema::create('upload_document', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['local', 'import']); // Type of entry, either local or import
            $table->string('lcpo_no'); // LC or PO Reference number
            $table->enum('part_shipment', ['yes', 'no']); // Part shipment dropdown
            $table->decimal('total_amount', 10, 2); // Total amount
            $table->string('lc_document')->nullable(); // LC document file
            $table->string('requisition_document')->nullable(); // Requisition document file
            $table->string('management_approval_document')->nullable(); // Management approval document file
            $table->string('purchase_order_document')->nullable(); // Purchase order document file
            $table->string('regulatory_approval_document')->nullable(); // Regulatory approval document file
            $table->string('invoice_document')->nullable(); // Invoice document file
            $table->string('customs_document')->nullable(); // Customs document file
            $table->text('remarks')->nullable(); // Remarks section
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('upload_document');
    }
}
