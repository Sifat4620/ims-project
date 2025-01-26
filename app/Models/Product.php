<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Define the fields that are mass assignable
    protected $fillable = [
        'product_id',
        'category',
        'product_brand',
        'entry_date',
    ];
}
