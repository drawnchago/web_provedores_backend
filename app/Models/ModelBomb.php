<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelBomb extends Model
{
    protected $table = "Tbl_Op_Models";
    protected $fillable = [
        'id',
        'name',
        'description',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];

}