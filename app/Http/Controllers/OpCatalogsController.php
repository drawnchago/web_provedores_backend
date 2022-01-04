<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\ModelBomb;
use App\Models\TypesBomb;
use App\Models\BrandBomb;
use App\Models\Customers;
use App\Models\User;
use App\Models\Role;
use App\Models\KindOfPerson;
use App\Models\CFDI;
use App\Models\Bank;

class OpCatalogsController extends Controller
{
    public function __construct()
    {
        //
    }
    //TYPES BOMBS
    public function getTypesBomb(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $types_bomb = TypesBomb::Leftjoin('users as mod','mod.id','Tbl_Op_TypesBomb.updated_by')->Leftjoin('users as created','created.id','Tbl_Op_TypesBomb.created_by')
        ->select('Tbl_Op_TypesBomb.id','Tbl_Op_TypesBomb.name','Tbl_Op_TypesBomb.description','Tbl_Op_TypesBomb.status','mod.username as updated_by','Tbl_Op_TypesBomb.updated_at','created.username as created_by','Tbl_Op_TypesBomb.created_at')->get();

        if(count($types_bomb) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['types_bomb'] = $types_bomb;

        return $response;
    }

    public function saveTypeBomb(Request $req){

        $response = ['success' => false ,'message' => "No se guardó correctamente"];

        $id          = $req->id;
        $name        = $req->name;
        $description = $req->description;
        $status      = $req->status;
        $user_id     = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                TypesBomb::create([
                    'name'        => $name,
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
                    TypesBomb::where('id',$id)->update([
                        'name'        => $name,
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

    public function deleteTypeBomb(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            TypesBomb::where('id',$id)->update([
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
    //BRANDS
    public function getBrandsBomb(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $brands_bomb = BrandBomb::Leftjoin('users as mod','mod.id','Tbl_Op_Brands.updated_by')->Leftjoin('users as created','created.id','Tbl_Op_Brands.created_by')
        ->select('Tbl_Op_Brands.id','Tbl_Op_Brands.name','Tbl_Op_Brands.description','Tbl_Op_Brands.status','mod.username as updated_by','Tbl_Op_Brands.updated_at','created.username as created_by','Tbl_Op_Brands.created_at')->get();

        if(count($brands_bomb) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['brands_bomb'] = $brands_bomb;

        return $response;
    }

    public function saveBrandBomb(Request $req){

        $response = ['success' => false ,'message' => "No se guardó correctamente"];

        $id          = $req->id;
        $name        = $req->name;
        $description = $req->description;
        $status      = $req->status;
        $user_id     = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                BrandBomb::create([
                    'name'        => $name,
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
                    BrandBomb::where('id',$id)->update([
                        'name'        => $name,
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

    public function deleteBrandBomb(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            BrandBomb::where('id',$id)->update([
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
    //MODELS
    public function getModelsBomb(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $models_bomb = ModelBomb::Leftjoin('users as mod','mod.id','Tbl_Op_Models.updated_by')->Leftjoin('users as created','created.id','Tbl_Op_Models.created_by')
        ->select('Tbl_Op_Models.id','Tbl_Op_Models.name','Tbl_Op_Models.description','Tbl_Op_Models.status','mod.username as updated_by','Tbl_Op_Models.updated_at','created.username as created_by','Tbl_Op_Models.created_at')->get();

        if(count($models_bomb) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['models_bomb'] = $models_bomb;

        return $response;
    }

    public function saveModelBomb(Request $req){

        $response = ['success' => false ,'message' => "No se guardó correctamente"];

        $id          = $req->id;
        $name        = $req->name;
        $description = $req->description;
        $status      = $req->status;
        $user_id     = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                ModelBomb::create([
                    'name'        => $name,
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
                    ModelBomb::where('id',$id)->update([
                        'name'        => $name,
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

    public function deleteModelBomb(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            ModelBomb::where('id',$id)->update([
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

    //CUSTOMERS
    public function getCustomers(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $customers = Customers::Leftjoin('users as mod','mod.id','Tbl_Op_Customers.updated_by')
        ->Leftjoin('users as created','created.id','Tbl_Op_Customers.created_by')
        ->Leftjoin('Tbl_Cat_States as state','state.id','Tbl_Op_Customers.state_id')
        ->select(
        'Tbl_Op_Customers.id',
        'Tbl_Op_Customers.name',
        'Tbl_Op_Customers.description',
        'Tbl_Op_Customers.adress',
        'Tbl_Op_Customers.subrub',
        'Tbl_Op_Customers.municipality_id',
        'Tbl_Op_Customers.state_id',
        'Tbl_Op_Customers.telephone',
        'Tbl_Op_Customers.rfc',
        'Tbl_Op_Customers.cp',
        'Tbl_Op_Customers.cfdi_id',
        'Tbl_Op_Customers.contact_purchase',
        'Tbl_Op_Customers.contact_payments',
        'Tbl_Op_Customers.bank_id',
        'Tbl_Op_Customers.email',
        'Tbl_Op_Customers.days',
        'Tbl_Op_Customers.account_bank',
        'Tbl_Op_Customers.kind_of_person_id',
        'Tbl_Op_Customers.credit_limit',
        'Tbl_Op_Customers.status',
        'mod.username as updated_by',
        'Tbl_Op_Customers.updated_at',
        'created.username as created_by',
        'Tbl_Op_Customers.created_at')->get();

        if(count($customers) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['customers'] = $customers;

        return $response;
    }

    public function saveCustomer(Request $req){
        $response = ['success' => false ,'message' => "No se guardó correctamente"];

        $id                = $req->id;
        $name              = $req->name;
        $description       = $req->description;
        $adress            = $req->adress;
        $subrub            = $req->subrub;
        $state_id          = $req->state_id;
        $municipality_id   = $req->municipality_id;
        $telephone         = $req->telephone;
        $rfc               = $req->rfc;
        $cp                = $req->cp;
        $cfdi_id           = $req->cfdi_id;
        $contact_purchase  = $req->contact_purchase;
        $contact_payments  = $req->contact_payments;
        $bank_id           = $req->bank_id;
        $email             = $req->email;
        $days              = $req->days;
        $account_bank      = $req->account_bank;
        $kind_of_person_id = $req->kind_of_person_id;
        $credit_limit      = $req->credit_limit;
        $status            = $req->status;
        $user_id           = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                Customers::create([
                    'name'              => $name,
                    'description'       => $description,
                    "adress"            => $adress,
                    "subrub"            => $subrub,
                    "state_id"          => $state_id,
                    "municipality_id"   => $municipality_id,
                    "telephone"         => $telephone,
                    "rfc"               => $rfc,
                    "cp"                => $cp,
                    "cfdi_id"           => $cfdi_id,
                    "contact_purchase"  => $contact_purchase,
                    "contact_payments"  => $contact_payments,
                    "bank_id"           => $bank_id,
                    "email"             => $email,
                    "days"              => $days,
                    "account_bank"      => $account_bank,
                    "kind_of_person_id" => $kind_of_person_id,
                    "credit_limit"      => $credit_limit,
                    'status'            => $status,
                    'updated_by'        => $user_id,
                    'created_by'        => $user_id
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
                    Customers::where('id',$id)->update([
                        'name'             => $name,
                        'description'      => $description,
                        "adress"            => $adress,
                        "subrub"            => $subrub,
                        "state_id"          => $state_id,
                        "municipality_id"   => $municipality_id,
                        "telephone"         => $telephone,
                        "rfc"               => $rfc,
                        "cp"                => $cp,
                        "cfdi_id"           => $cfdi_id,
                        "contact_purchase"  => $contact_purchase,
                        "contact_payments"  => $contact_payments,
                        "bank_id"           => $bank_id,
                        "email"             => $email,
                        "days"              => $days,
                        "account_bank"      => $account_bank,
                        "kind_of_person_id" => $kind_of_person_id,
                        "credit_limit"      => $credit_limit,
                        'status'            => $status,
                        'updated_by'        => $user_id
                    ]);
        
                    $response['success'] = true;
                    $response['message'] = "Se guardó registro.";
                    $response['acc'] = $account_bank;
        
                }catch(Exception $e){
                    $response['success'] = false;
                    $response['message'] = "No se guardó registro.";
        
                }
            }
        }

        return $response;
    }

    public function deleteCustomer(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            Customers::where('id',$id)->update([
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
	
	//BANK
    public function getBanks(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $banks = Bank::Leftjoin('users as mod','mod.id','Tbl_Cat_Banks.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_Banks.created_by')
        ->select('Tbl_Cat_Banks.id','Tbl_Cat_Banks.name','Tbl_Cat_Banks.description','Tbl_Cat_Banks.status','mod.username as updated_by','Tbl_Cat_Banks.updated_at','created.username as created_by','Tbl_Cat_Banks.created_at')->get();

        if(count($banks) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['banks'] = $banks;

        return $response;
    }
    //KINDOFPERSONS
    public function getKindOfPersons(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $kind_of_persons = KindOfPerson::Leftjoin('users as mod','mod.id','Tbl_Cat_KindOfPersons.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_KindOfPersons.created_by')
        ->select('Tbl_Cat_KindOfPersons.id','Tbl_Cat_KindOfPersons.name','Tbl_Cat_KindOfPersons.description','Tbl_Cat_KindOfPersons.status','mod.username as updated_by','Tbl_Cat_KindOfPersons.updated_at','created.username as created_by','Tbl_Cat_KindOfPersons.created_at')->get();

        if(count($kind_of_persons) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['kind_of_persons'] = $kind_of_persons;

        return $response;
    }
    //CFDI
    public function getCFDI(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $cfdi = CFDI::Leftjoin('users as mod','mod.id','Tbl_Cat_Cfdi.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_Cfdi.created_by')
        ->select('Tbl_Cat_Cfdi.id','Tbl_Cat_Cfdi.code','Tbl_Cat_Cfdi.description','Tbl_Cat_Cfdi.status','mod.username as updated_by','Tbl_Cat_Cfdi.updated_at','created.username as created_by','Tbl_Cat_Cfdi.created_at')->get();

        if(count($cfdi) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['cfdi'] = $cfdi;

        return $response;
    }
}
