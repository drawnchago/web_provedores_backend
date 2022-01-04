<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Products;
use App\Models\User;
use App\Models\Role;

class PrCatalogsController extends Controller
{
    public function __construct()
    {
        //
    }
    public function getProducts(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $products = Products::Leftjoin('users as mod','mod.id','Tbl_Cat_Products.updated_by')
        ->Leftjoin('users as created','created.id','Tbl_Cat_Products.created_by')
        ->Leftjoin('Tbl_Cat_MeasurementUnits','Tbl_Cat_Products.measurement_unit_id','Tbl_Cat_MeasurementUnits.id')
        ->Leftjoin('Tbl_Cat_Classifications','Tbl_Cat_Products.classification_id','Tbl_Cat_Classifications.id')
        ->Leftjoin('Tbl_Cat_TypesOfProducts','Tbl_Cat_Products.type_product_id','Tbl_Cat_TypesOfProducts.id')
        ->where('Tbl_Cat_Products.status',1)
        ->select('Tbl_Cat_Products.id',
        'Tbl_Cat_Products.measurement_unit_id',
        'Tbl_Cat_Products.classification_id',
        'Tbl_Cat_Products.type_product_id',
        'Tbl_Cat_Products.code',
        'Tbl_Cat_Products.description',
        'Tbl_Cat_MeasurementUnits.description as measurement_description',
        'Tbl_Cat_Classifications.description as classification_description',
        'Tbl_Cat_TypesOfProducts.description as type_product_description',
        'Tbl_Cat_Products.status',
        'mod.username as updated_by',
        'Tbl_Cat_Products.updated_at','created.username as created_by','Tbl_Cat_Products.created_at')->get();

        if(count($products) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['products'] = $products;

        return $response;
    }

    public function saveProduct(Request $req){

        $response = ['success' => false ,'message' => "No se guardó correctamente"];

        $id                  = $req->id;
        $measurement_unit_id = $req->measurement_unit_id;
        $type_product_id     = $req->type_product_id;
        $classification_id   = $req->classification_id;
        $name                = $req->name;
        $description         = $req->description;
        $status              = $req->status;
        $user_id             = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                Products::create([
                    'measurement_unit_id' => $measurement_unit_id,
                    'classification_id'   => $classification_id,
                    'type_product_id'     => $type_product_id,
                    'description'         => $description,
                    'status'              => $status,
                    'updated_by'          => $user_id,
                    'created_by'          => $user_id
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
                    Products::where('id',$id)->update([
                        'description'         => $description,
                        'measurement_unit_id' => $measurement_unit_id,
                        'classification_id'   => $classification_id,
                        'type_product_id'     => $type_product_id,
                        'status'              => $status,
                        'updated_by'          => $user_id
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

    public function deleteProduct(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            Products::where('id',$id)->update([
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
