<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Warehouses;
use App\Models\User;
use App\Models\Role;

class WaCatalogsController extends Controller
{
    public function __construct()
    {
        //
    }
    public function getWarehouses(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $warehouses = Warehouses::Leftjoin('users as mod','mod.id','Tbl_Cat_Warehouses.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_Warehouses.created_by')
        ->select('Tbl_Cat_Warehouses.id','Tbl_Cat_Warehouses.description','Tbl_Cat_Warehouses.status','mod.username as updated_by','Tbl_Cat_Warehouses.updated_at','created.username as created_by','Tbl_Cat_Warehouses.created_at')->get();

        if(count($warehouses) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['warehouses'] = $warehouses;

        return $response;
    }

    public function saveWarehouses(Request $req){

        $response = ['success' => false ,'message' => "No se guardó correctamente"];

        $id          = $req->id;
        $name        = $req->name;
        $description = $req->description;
        $status      = $req->status;
        $user_id     = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                Warehouses::create([
                    'description' => $description,
                    'status'      => $status,
                    'updated_by'  => $user_id,
                    'created_by'  => $user_id
                ]);
    
                $response['success'] = true;
                $response['message'] = "Se guardó registro.";
    
            }catch(Exception $e){
                $response['success'] = false;
                $response['message'] = "No se guardó registro.";
    
            }
        }else{
            if($id){
                try {
                    Warehouses::where('id',$id)->update([
                        'description' => $description,
                        'status'      => $status,
                        'updated_by'  => $user_id
                    ]);
        
                    $response['success'] = true;
                    $response['message'] = "Se guardó registro.";
        
                }catch(Exception $e){
                    $response['success'] = false;
                    $response['message'] = "No se guardó registro.";
        
                }
            }
        }

        return $response;
    }

    public function deleteWarehouses(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            Warehouses::where('id',$id)->update([
                'status'=>0,
            ]);

            $response['success'] = true;
            $response['message'] = "Se desactivó registro.";

        }catch(Exception $e){
            $response['success'] = false;
            $response['message'] = "No se desactivó registro.";

        }

        return $response;
    }
    
}
