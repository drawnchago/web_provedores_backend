<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Classifications;
use App\Models\User;
use App\Models\Role;

class ClCatalogsController  extends Controller
{
    public function __construct()
    {
        //
    }
    public function getClassifications(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $classifications = Classifications::Leftjoin('users as mod','mod.id','Tbl_Cat_Classifications.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_Classifications.created_by')
        ->select('Tbl_Cat_Classifications.id','Tbl_Cat_Classifications.description','Tbl_Cat_Classifications.status','mod.username as updated_by','Tbl_Cat_Classifications.updated_at','created.username as created_by','Tbl_Cat_Classifications.created_at')->get();

        if(count($classifications) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['classifications'] = $classifications;

        return $response;
    }

    public function saveClassifications(Request $req){

        $response = ['success' => false ,'message' => "No se guardó correctamente"];

        $id          = $req->id;
        $description = $req->description;
        $status      = $req->status;
        $user_id     = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                Classifications::create([
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
                    Classifications::where('id',$id)->update([
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

    public function deleteClassifications(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            Classifications::where('id',$id)->update([
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
