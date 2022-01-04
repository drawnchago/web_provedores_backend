<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeOfProducts extends Model
{
    protected $table = "Tbl_Cat_TypesOfProducts";
    protected $fillable = [
        'id',
        'description',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

}