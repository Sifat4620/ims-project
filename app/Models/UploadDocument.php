<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadDocument extends Model
{
    // Specify the table name explicitly if it doesn't follow Laravel's plural naming convention
    protected $table = 'upload_document'; // Match the table name in the database

    // Define the fillable attributes (optional)
    protected $fillable = [
        'type',
        'lcpo_no',
        'part_shipment',
        'total_amount',
        'lc_document',
        'requisition_document',
        'management_approval_document',
        'purchase_order_document',
        'regulatory_approval_document',
        'invoice_document',
        'customs_document',
        'remarks',
    ];
}