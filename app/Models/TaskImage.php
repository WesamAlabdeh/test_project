<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskImage extends BaseModel
{
    //@relations 
    
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
