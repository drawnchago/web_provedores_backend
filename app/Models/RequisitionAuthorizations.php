<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionAuthorizations extends Model
{
    protected $table = "Tbl_Sho_RequisitionAuthorizations";
    protected $fillable = [
        'id',
        'purchase_requisition_id',
        'user_id',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

}