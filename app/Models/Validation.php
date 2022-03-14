<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Validation extends Model
{
    protected $fillable = [
        "id",
        "description",
        "status",
        "created_at",
        "updated_at",
        "updated_by"
    ];

}