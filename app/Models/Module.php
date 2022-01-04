<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    protected $fillable = [
        'id',
        'parentId',
        'name',
        'state',
        'href',
        'icon',
        'target',
        'position',
        'pathMatchExact',
        'deleted',
        'badge',
        'badgeColor',
        'type',
        'created_by',
        'updated_by',
    ];

}