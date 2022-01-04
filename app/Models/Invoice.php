<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'Tbl_Adm_Invoices';
    protected $fillable = [
        'id',
        'customer_id',
        'serie',
        'invoice_number',
        'uuid',
        'amount',
        'comments',
        'digits',
        'payment_method',
        'payment_way',
        'cfdi',
        'stamp_date',
        'nom_cer_sat',
        'stamped',
        'xml',
        'pdf',
        'status',
        'updated_by',
        'updated_at',
        'created_by',
        'created_at',
    ];
}
