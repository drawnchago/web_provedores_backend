<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CFDI extends Model
{
    protected $table = "Tbl_Cat_Cfdi";
    protected $fillable = [
        "id",
        "code",
        "description",
        "status",
        "updated_at",
        "updated_by",
        "created_at",
        "created_by",
    ];

}