<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntryEquipment extends Model
{
    protected $table = 'Tbl_Op_WorkOrderEquipmentEntrys';
    protected $fillable = [

        'id',
        'position_order',
        'entry_date',
        'user_id',
        'zone',
        'equipment_description',
        'place',
        'type',
        'description_entry',
        'comments_coditions',
        'equipment_application',
        'handling_fluid',
        'folio_equipment',
        'work_temperature',
        'exposed_pressure',
        'number_or_folio_requisition',
        'applicant',
        'witness',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'priority_id',
    ];
}
