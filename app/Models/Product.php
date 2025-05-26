<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id'; 
    public $incrementing = true;
    protected $keyType = 'int'; 

    // Define the fields that are mass assignable
    protected $fillable = [
        'product_id',
        'category',
        'product_brand',
        'entry_date',
    ];
}
