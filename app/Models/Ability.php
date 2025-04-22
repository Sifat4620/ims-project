<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    // Define the polymorphic relationship with roles
    public function roles()
    {
        return $this->morphToMany(Role::class, 'ability');
    }
}
