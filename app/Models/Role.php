<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    protected $fillable = [
        'id',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];

}