<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    protected $connection = 'avalanz';
    protected $table = "DADOSADV.ZWP200";
    protected $fillable = [
        "zwp_filial",
        "zwp_cod",
        "zwp_loja",
        "zwp_nome",
        "zwp_nreduz",
        "zwp_end",
        "zwp_nr_end",
        "zwp_bairro",
        "zwp_tel",
        "zwp_est",
        "zwp_cod_mu",
        "zwp_estado",
        "zwp_mun",
        "zwp_cep",
        "zwp_tipo",
        "zwp_cgc",
        "zwp_fax",
        "zwp_contat",
        "zwp_banco",
        "zwp_agenci",
        "zwp_numcon",
        "zwp_nature",
        "zwp_pais",
        "zwp_depto",
        "zwp_status",
        "zwp_grupo",
        "zwp_repres",
        "zwp_reprte",
        "zwp_empori",
        "zwp_email",
        "zwp_hpage",
        "zwp_codint",
        "zwp_curp",
        "zwp_msblql",
        "zwp_userpo",
        "zwp_dtacre",
        "d_e_l_e_t_",
        "r_e_c_n_o_",
        "zwp_unico"
    ];

}