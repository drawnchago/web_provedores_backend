<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    protected $table = "Tbl_Sho_PurchaseOrders";
    protected $fillable = [
        "id",
        "purchase_requisition_id",
        "provider_id",
        "subtotal",
        "iva",
        "total",
        "authorization_date",
        "authorization_by",
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
    ];

}