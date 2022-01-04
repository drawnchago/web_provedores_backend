<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\TypeOfProducts;
use App\Models\User;
use App\Models\Role;

class TpCatalogsController  extends Controller
{
    public function __construct()
    {
        //
    }
    public function getTypeOfProducts(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $type_of_products = TypeOfProducts::Leftjoin('users as mod','mod.id','Tbl_Cat_TypesOfProducts.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_TypesOfProducts.created_by')
        ->select('Tbl_Cat_TypesOfProducts.id','Tbl_Cat_TypesOfProducts.description','Tbl_Cat_TypesOfProducts.status','mod.username as updated_by','Tbl_Cat_TypesOfProducts.updated_at','created.username as created_by','Tbl_Cat_TypesOfProducts.created_at')->get();

        if(count($type_of_products) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['type_of_products'] = $type_of_products;

        return $response;
    }

    public function saveTypeOfProduct(Request $req){

        $response = ['success' => false ,'message' => "No se guardó correctamente"];

        $id          = $req->id;
        $description = $req->description;
        $status      = $req->status;
        $user_id     = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                TypeOfProducts::create([
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
                    TypeOfProducts::where('id',$id)->update([
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

    public function deleteTypeOfProduct(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            TypeOfProducts::where('id',$id)->update([
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
