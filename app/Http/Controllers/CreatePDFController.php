<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\BranchOffices;
use App\Models\ModelBomb;
use App\Models\TypesBomb;
use App\Models\BrandBomb;
use App\Models\Customers;
use App\Models\User;
use App\Models\Role;

class CreatePDFController extends Controller
{
    public function __construct()
    {
        //
    }
    public function getBranchOffice(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $branchOffices = BranchOffices::where('id',$req->id)->first();

        if($branchOffices){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['branchOffices'] = $branchOffices;

        return $response;
    }

}
