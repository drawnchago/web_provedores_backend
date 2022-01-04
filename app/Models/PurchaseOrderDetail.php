<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderDetail extends Model
{
    protected $table = "Tbl_Sho_PurchaseOrderDetails";
    protected $fillable = [
        "id",
        "purchaseorder_id",
        "purchaserequisitions_id",
        "product_id",
        "unit_price",
        "quantity",
        "total",
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
    ];

}