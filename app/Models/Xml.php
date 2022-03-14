<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Xml extends Model
{
    protected $table = "xml";
    protected $fillable = [
        'id',
        'nombre_receptor',
        'rfc_receptor',
        'cfdi_receptor',
        'moneda',
        'tipo_comprobante',
        'folio',
        'forma_pago',
        'no_certificado',
        'subtotal',
        'tipo_cambio',
        'metodo_pago',
        'lugar_expedicion',
        'fecha',
        'version',
        'total',
        'uuid',
        'no_certificado_sat',
        'sello_cfdi',
        'sello_sat',
        'cadOri',
        'CadImpTot',
        'created_at',
        'updated_at',
        'updated_by',
    ];

}