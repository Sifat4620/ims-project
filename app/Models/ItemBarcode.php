<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemBarcode extends Model
{
    protected $fillable = ['item_id', 'product_id', 'downloaded_at'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    
}
