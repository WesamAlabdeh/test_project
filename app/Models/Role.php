<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends BaseModel
{
    protected static $filtersArray = [
        'name' => 'equal',
    ];

    //@relations 

    public function abilities() : BelongsToMany
    {
        return $this->belongsToMany(Ability::class, 'role_abilities', 'role_id', 'ability_name', 'id', 'name');
    }
}
