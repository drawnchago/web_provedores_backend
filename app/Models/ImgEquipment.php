<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImgEquipment extends Model
{
    //
    protected $table = 'Tbl_Op_ImgEnquipment';
    protected $fillable = [
        'id',
        'order_id',
        'path',
        'type',
        'updated_by',
        'updated_at',
        'created_by',
        'created_at',
    ];
}
