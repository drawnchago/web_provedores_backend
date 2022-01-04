<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Levels extends Model
{
    protected $table = "Tbl_Cat_Levels";
    protected $fillable = [
        'id',
        'area_id',
        'user_id',
        'position',
        'level',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

}