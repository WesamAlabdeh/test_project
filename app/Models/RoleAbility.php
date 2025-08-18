<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleAbility extends BaseModel
{

    //@relations 

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function ability(): BelongsTo
    {
        return $this->belongsTo(Ability::class, 'ability_name');
    }
}
