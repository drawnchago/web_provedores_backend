<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExitEquipment extends Model
{
    //
    protected $table = 'Tbl_Op_WorkOrderEquipmentExits';
    protected $fillable = [
        'id',
        'invoice_or_referral',
        'exit_pass',
        'user_id',
        'zone',
        'exit_date',
        'equipment_folio',
        'drips',
        'order',
        'type',
        'material_description',
        'test_pressure',
        'leakage',
        'arrow_end_dimension',
        'threads',
        'screws_cooling_lines',
        'armed',
        'keyhole',
        'levels',
        'packaging',
        'observations',
        'applicant',
        'witness',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];
}
