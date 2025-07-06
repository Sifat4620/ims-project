<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemBarcode extends Model
{
    use HasFactory;

    protected $table = 'item_barcodes';

    // Fields allowed for mass assignment
    protected $fillable = [
        'item_id',
        'product_id',
        'barcode_string',
        'downloaded_at',
    ];

    // Relationships
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
