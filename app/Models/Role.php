<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Silber\Bouncer\Database\Role as BouncerRole; // Bouncer's Role
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Role extends BouncerRole
{
    use HasFactory;

    // Specify the table name if it's not the default 'roles'
    protected $table = 'roles';

    // Allow mass assignment for the 'name', 'title', and 'scope' fields
    protected $fillable = ['name', 'title', 'scope'];

    /**
     * Define the abilities associated with this role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function abilities(): MorphToMany
    {
        return $this->morphedByMany(Ability::class, 'ability');
    }
}
