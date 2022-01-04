<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionDetails extends Model
{
    protected $table = "Tbl_Sho_RequisitionDetails";
    protected $fillable = [
        "id",
        "purchase_requisition_id",
        "product_id",
        "unit_price",
        "quantity",
        "subtotal",
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
    ];

}