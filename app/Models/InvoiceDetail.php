<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoie extends Model
{
    protected $table = 'Tbl_Adm_InvoicesDetail';
    protected $fillable = [
        'id',
        'invoice_id',
        'order_id',
        'updated_by',
        'updated_at',
        'created_by',
        'created_at',
    ];
}
