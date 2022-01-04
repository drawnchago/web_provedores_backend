<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\QuotationConcept;
use App\Models\Customers;

class Quotation extends Model
{
    protected $table = "Tbl_Sales_Quotations";
    protected $fillable = [
        'id',
        'customer_id',
        'branch_office_id',
        'opportunity_id',
        'activity_id',
        'comments',
        'contact',
        'telephone',
        'email',
        'sent',
        'import',
        'subtotal',
        'iva',
        'discount',
        'discount_p',
        'total',
        'status',
        'accepted',
        'created_by',
        'updated_by'
    ];

    public function concepts(){
        return $this->hasMany(QuotationConcept::class, 'quotation_id');
    }

    public function customer(){
        return $this->belongsTo(Customers::class,'customer_id');
    }

}