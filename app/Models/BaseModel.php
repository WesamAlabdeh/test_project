<?php

namespace App\Models;

use App\Traits\Filters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory, Filters;
    protected $guarded = ['id'];
    protected $perPage = 10;
    protected $hidden = [
        'updated_at',
        'created_at'
    ];
}
