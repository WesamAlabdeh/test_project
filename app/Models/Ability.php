<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ability extends BaseModel
{
    protected static $filtersArray = [
        'name' => 'like',
    ];

    //@relations 

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_abilities', 'ability_name', 'user_id', 'name', 'id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_abilities', 'ability_name', 'role_id', 'name', 'id');
    }
}
