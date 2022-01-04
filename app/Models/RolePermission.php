<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RolePermission extends Model
{
    protected $fillable = [
        'role_id',
        'user_id',
        'module_id',
        'edit',
        'delete'
    ];

    public function module() {
		return $this->belongsTo('App\Models\Module');
	}
}
