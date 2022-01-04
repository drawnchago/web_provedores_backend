<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderDetail extends Model
{
    protected $table = 'Tbl_Op_WorkOrderDetail';
    protected $fillable = [
        'id',
        'order_id',
        'description',
        'status',
        'updated_by',
        'updated',
        'updated_at',
        'created_by',
        'created_at',
    ];
}
