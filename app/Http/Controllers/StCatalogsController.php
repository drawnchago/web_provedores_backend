<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Countries;
use App\Models\State;
use App\Models\Municipality;
use App\Models\Areas;
use App\Models\PaymentMethods;
use App\Models\Coins;
use App\Models\User;
use App\Models\Role;

class StCatalogsController  extends Controller
{
    public function __construct()
    {
        //
    }
    public function getCountries(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $countries = Countries::Leftjoin('users as mod','mod.id','Tbl_Cat_Countries.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_Countries.created_by')
        ->select('Tbl_Cat_Countries.id','Tbl_Cat_Countries.description','Tbl_Cat_Countries.status','mod.username as updated_by','Tbl_Cat_Countries.updated_at','created.username as created_by','Tbl_Cat_Countries.created_at')->get();

        if(count($countries) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['countries'] = $countries;

        return $response;
    }

    public function saveCountry(Request $req){

        $response = ['success' => false ,'message' => "No se guardó correctamente"];

        $id          = $req->id;
        $description = $req->description;
        $status      = $req->status;
        $user_id     = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                Countries::create([
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
                    Countries::where('id',$id)->update([
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

    public function deleteCountry(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            Countries::where('id',$id)->update([
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
    
    public function getStates(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $states   = State::Leftjoin('users as mod','mod.id','Tbl_Cat_States.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_States.created_by')
        ->select('Tbl_Cat_States.id','Tbl_Cat_States.name','Tbl_Cat_States.status','mod.username as updated_by','Tbl_Cat_States.updated_at','created.username as created_by','Tbl_Cat_States.created_at')->get();

        if(count($states) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['states'] = $states;

        return $response;
    }

    public function saveState(Request $req){

        $response = ['success' => false ,'message' => "No se guardó correctamente"];

        $id          = $req->id;
        $description = $req->description;
        $status      = $req->status;
        $user_id     = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                State::create([
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
                    State::where('id',$id)->update([
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

    public function deleteState(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            State::where('id',$id)->update([
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
    
    public function getMunicipalities(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $municipalities   = Municipality::Leftjoin('users as mod','mod.id','Tbl_Cat_Municipalities.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_Municipalities.created_by')
        ->select('Tbl_Cat_Municipalities.id','Tbl_Cat_Municipalities.description','Tbl_Cat_Municipalities.status','mod.username as updated_by','Tbl_Cat_Municipalities.updated_at','created.username as created_by','Tbl_Cat_Municipalities.created_at')->get();

        if(count($municipalities) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['municipalities'] = $municipalities;

        return $response;
    }

    public function saveMunicipality(Request $req){

        $response = ['success' => false ,'message' => "No se guardó correctamente"];

        $id          = $req->id;
        $description = $req->description;
        $status      = $req->status;
        $user_id     = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                Municipality::create([
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
                    Municipality::where('id',$id)->update([
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

    public function deleteMunicipality(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            Municipality::where('id',$id)->update([
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

    public function getAreas(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $areas   = Areas::Leftjoin('users as mod','mod.id','Tbl_Cat_Areas.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_Areas.created_by')
        ->select('Tbl_Cat_Areas.id','Tbl_Cat_Areas.description','Tbl_Cat_Areas.status','mod.username as updated_by','Tbl_Cat_Areas.updated_at','created.username as created_by','Tbl_Cat_Areas.created_at')->get();

        if(count($areas) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['areas'] = $areas;

        return $response;
    }

    public function saveArea(Request $req){

        $response = ['success' => false ,'message' => "No se guardó correctamente"];

        $id          = $req->id;
        $description = $req->description;
        $status      = $req->status;
        $user_id     = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                Areas::create([
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
                    Areas::where('id',$id)->update([
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

    public function deleteArea(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            Areas::where('id',$id)->update([
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
    public function getPaymentMethods(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $payment_methods   = PaymentMethods::Leftjoin('users as mod','mod.id','Tbl_Cat_PaymentMethods.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_PaymentMethods.created_by')
        ->select('Tbl_Cat_PaymentMethods.id','Tbl_Cat_PaymentMethods.description','Tbl_Cat_PaymentMethods.status','mod.username as updated_by','Tbl_Cat_PaymentMethods.updated_at','created.username as created_by','Tbl_Cat_PaymentMethods.created_at')->get();

        if(count($payment_methods) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['payment_methods'] = $payment_methods;

        return $response;
    }

    public function savePaymentMethod(Request $req){

        $response = ['success' => false ,'message' => "No se guardó correctamente"];

        $id          = $req->id;
        $description = $req->description;
        $status      = $req->status;
        $user_id     = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                PaymentMethods::create([
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
                    PaymentMethods::where('id',$id)->update([
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

    public function deletePaymentMethod(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            PaymentMethods::where('id',$id)->update([
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
    public function getCoins(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $coins   = Coins::Leftjoin('users as mod','mod.id','Tbl_Cat_Coins.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_Coins.created_by')
        ->select('Tbl_Cat_Coins.id','Tbl_Cat_Coins.description','Tbl_Cat_Coins.status','mod.username as updated_by','Tbl_Cat_Coins.updated_at','created.username as created_by','Tbl_Cat_Coins.created_at')->get();

        if(count($coins) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['coins'] = $coins;

        return $response;
    }

    public function saveCoin(Request $req){

        $response = ['success' => false ,'message' => "No se guardó correctamente"];

        $id          = $req->id;
        $description = $req->description;
        $status      = $req->status;
        $user_id     = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                Coins::create([
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
                    Coins::where('id',$id)->update([
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

    public function deleteCoin(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            Coins::where('id',$id)->update([
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
