<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TaskImage extends BaseModel
{
    protected static function booted()
    {
        static::deleting(function (TaskImage $taskImage) {
            if ($taskImage->image) {
                Storage::disk('public')->delete($taskImage->image);
            }
        });

        static::updating(function (TaskImage $taskImage) {
            $original = $taskImage->getOriginal('image');
            if ($taskImage->isDirty('image') && $original) {
                Storage::disk('public')->delete($original);
            }
        });
    }

    //@relations 

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
