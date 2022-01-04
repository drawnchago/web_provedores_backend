<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customers extends Model
{
    protected $table = "Tbl_Op_Customers";
    protected $fillable = [
        "id",
        "name",
        "description",
        "adress",
        "subrub",
        "municipality_id",
        "state_id",
        "telephone",
        "rfc",
        "cp",
        "cfdi_id",
        "contact_purchase",
        "contact_payments",
        "bank_id",
        "email",
        "days",
        "account_bank",
        "kind_of_person_id",
        "credit_limit",
        "status",
        "updated_at",
        "updated_by",
        "created_at",
        "created_by"
    ];

}