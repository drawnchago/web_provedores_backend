<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    protected $fillable = [
        'id',
        'role_id',
        'name',
        'firstname',
        'surname',
        'username',
        'password',
        'email',
        'status',
        'created_at',
        'updated_at',
    ];

}