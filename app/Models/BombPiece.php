<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BombPiece extends Model
{
    protected $table = 'Tbl_Op_PiecesBombs';
    protected $fillable = [
        'id',
        'type_bomb_id',
        'name',
        'description',
        'type_piece',
        'status',
        'updated_by',
        'updated',
        'updated_at',
        'created_by',
        'created_at',
    ];
}
