<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    protected $fillable = ['item_id', 'serial_no', 'price'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}