<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\MeasurementUnits;
use App\Models\User;
use App\Models\Role;

class MuCatalogsController extends Controller
{
    public function __construct()
    {
        //
    }
    public function getMeasurementUnits(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $measurement_units = MeasurementUnits::Leftjoin('users as mod','mod.id','Tbl_Cat_MeasurementUnits.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_MeasurementUnits.created_by')
        ->select('Tbl_Cat_MeasurementUnits.id','Tbl_Cat_MeasurementUnits.description','Tbl_Cat_MeasurementUnits.status','mod.username as updated_by','Tbl_Cat_MeasurementUnits.updated_at','created.username as created_by','Tbl_Cat_MeasurementUnits.created_at')->get();

        if(count($measurement_units) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['measurement_units'] = $measurement_units;

        return $response;
    }

    public function saveMeasurementUnit(Request $req){

        $response = ['success' => false ,'message' => "No se guardó correctamente"];

        $id          = $req->id;
        $description = $req->description;
        $status      = $req->status;
        $user_id     = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                MeasurementUnits::create([
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
                    MeasurementUnits::where('id',$id)->update([
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

    public function deleteMeasurementUnit(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            MeasurementUnits::where('id',$id)->update([
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
