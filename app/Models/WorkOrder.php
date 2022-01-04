<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    protected $table = 'Tbl_Op_WorkOrders';
    protected $fillable = [
        'id',
        'type_bomb_id',
        'customer_id',
        'brand_id',
        'model_id',
        'quotation_id',
        'size',
        'stock',
        'exit_pass',
        'rpm',
        'hp',
        'evaluation',
        'set',
        'total_length_quantity',
        'total_length_description',
        'total_diameter_quantity',
        'total_diameter_description',
        'total_weight_quantity',
        'total_weight_description',
        'status',
        'entry_id',
        'exit_id',
        'updated_by',
        'updated',
        'updated_at',
        'created_by',
        'created_at',
    ];
}
