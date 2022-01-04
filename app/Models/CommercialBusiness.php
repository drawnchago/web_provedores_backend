<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommercialBusiness extends Model
{
    protected $table = "Tbl_Cat_Commercial_business";
    protected $fillable = [
        "id",
        "name",
        "description",
        "status",
        "updated_at",
        "updated_by",
        "created_at",
        "created_by",
    ];

}