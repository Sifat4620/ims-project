<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Specify the table name if it's not the default 'roles'
    protected $table = 'roles';

    // Allow mass assignment for the 'designation' field
    protected $fillable = ['designation'];
}
