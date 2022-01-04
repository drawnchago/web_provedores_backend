<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    protected $table = "Tbl_Cat_Products";
    protected $fillable = [
        "id",
        "code",
        "description",
        "measurement_unit_id",
        "classification_id",
        "type_product_id",
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by"
    ];

}