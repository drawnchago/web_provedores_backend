<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentsOnRequisitions extends Model
{
    protected $table = "Tbl_Sho_CommentsOnRequisitions";
    protected $fillable = [
        'id',
        'purchase_requisition_id',
        'level_id',
        'user_id',
        'comments',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

}