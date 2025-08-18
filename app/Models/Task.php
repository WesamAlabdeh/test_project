<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends BaseModel
{
    protected $casts = [
        'status' => TaskStatusEnum::class,
    ];

    protected  static $filtersArray = [
        'title' => 'like',
        'description' => 'like',
        'user_id' => 'equal',
        'status' => 'equal',
    ];


    protected static function booted()
    {
        static::deleting(function (Task $task) {});

        static::updating(function (Task $task) {});
    }


    //@relations 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function images(): HasMany
    {
        return $this->hasMany(TaskImage::class);
    }
}
