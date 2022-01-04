<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleSetting extends Model
{
	protected $fillable = [
        'role_id',
        'module_id',
        'edit',
        'delete',
    ];
}
