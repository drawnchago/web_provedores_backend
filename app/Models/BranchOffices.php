<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchOffices extends Model
{
    protected $table = "Tbl_Sett_BranchOffices";
    protected $fillable = [
        'id',
        'code',
        'description',
        'street',
        'exterior_number',
        'interior_number',
        'subrub',
        'postal_code',
        'id_country',
        'id_state',
        'id_municipality',
        'cellphone',
        'phone',
        'phone_2',
        'email',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

}