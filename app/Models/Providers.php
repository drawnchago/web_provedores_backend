<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Providers extends Model
{
    protected $table = "Tbl_Cat_Providers";
    protected $fillable = [
        "id",
        "name",
        "commercial_business_id",
        "business_name",
        "adress",
        "subrub",
        "municipality_id",
        "state_id",
        "telephone",
        "contact_company",
        "contact_payment",
        "email",
        "rfc",
        "cp",
        "expense_account",
        "expense_subaccount",
        "fiscal_account",
        "limit",
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
    ];

}