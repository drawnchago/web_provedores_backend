<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspectionPiece extends Model
{
    protected $table = 'Tbl_Op_PiecesInspection';
    protected $fillable = [
        'id',
        'piece_bomb_id',
        'order_detail_id',
        'yes',
        'no',
        'repair',
        'supply',
        'demand',
        'stock',
        'description',
        'status',
        'updated_by',
        'updated',
        'updated_at',
        'created_by',
        'created_at',
    ];
}
