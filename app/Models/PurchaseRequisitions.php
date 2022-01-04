<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequisitions extends Model
{
    protected $table = "Tbl_Sho_PurchaseRequisitions";
    protected $fillable = [
        "id",
        "area_id",
        "comments",
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
    ];

}