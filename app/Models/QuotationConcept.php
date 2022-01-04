<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationConcept extends Model
{
    protected $table = "Tbl_Sales_QuotationConcepts";
    protected $fillable = [
        'id',
        'quotation_id',
        'concept_id',
        'code',
        'description',
        'unit',
        'quantity',
        'unit_price',
        'import',
        'concept_comment',
        'status',
        'created_by',
        'updated_by'
    ];

}