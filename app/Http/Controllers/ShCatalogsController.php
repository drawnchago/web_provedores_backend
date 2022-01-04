<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\PurchaseRequisitions;
use App\Models\Providers;
use App\Models\Cfdi;
use App\Models\Bank;
use App\Models\KindOfPerson;
use App\Models\CommercialBusiness;
use App\Models\User;
use App\Models\Role;

class ShCatalogsController  extends Controller
{
    public function __construct()
    {
        //
    }
    public function getProviders(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $providers = Providers::Leftjoin('users as mod','mod.id','Tbl_Cat_Providers.updated_by')
        ->Leftjoin('users as created','created.id','Tbl_Cat_Providers.created_by')
        ->Leftjoin('Tbl_Cat_States as state','state.id','Tbl_Cat_Providers.state_id')
        ->Leftjoin('Tbl_Cat_Municipalities as municipality','municipality.id','Tbl_Cat_Providers.municipality_id')
        ->select(
        'Tbl_Cat_Providers.id',
        'Tbl_Cat_Providers.commercial_business_id',
        'Tbl_Cat_Providers.name',
        'Tbl_Cat_Providers.business_name',
        'Tbl_Cat_Providers.adress',
        'Tbl_Cat_Providers.subrub',
        'Tbl_Cat_Providers.municipality_id',
        'municipality.name as desc_mun',
        'Tbl_Cat_Providers.state_id',
        'state.name as desc_state',
        'Tbl_Cat_Providers.telephone',
        'Tbl_Cat_Providers.contact_company',
        'Tbl_Cat_Providers.contact_payment',
        'Tbl_Cat_Providers.email',
        'Tbl_Cat_Providers.rfc',
        'Tbl_Cat_Providers.cp',
        'Tbl_Cat_Providers.expense_account',
        'Tbl_Cat_Providers.expense_subaccount',
        'Tbl_Cat_Providers.fiscal_account',
        'Tbl_Cat_Providers.limit',
        'Tbl_Cat_Providers.status',
        'mod.username as updated_by',
        'Tbl_Cat_Providers.updated_at',
        'created.username as created_by',
        'Tbl_Cat_Providers.created_at')->get();

        if(count($providers) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['providers'] = $providers;

        return $response;
    }

    public function saveProvider(Request $req){

        $response = ['success' => false ,
        'message' => "No se guardó correctamente"];

        $id                     = $req->id;
        $commercial_business_id = $req->commercial_business_id;
        $name                   = $req->name;
        $business_name          = $req->business_name;
        $adress                 = $req->adress;
        $subrub                 = $req->subrub;
        $state_id               = $req->state_id;
        $municipality_id        = $req->municipality_id;
        $telephone              = $req->telephone;
        $contact_company        = $req->contact_company;
        $contact_payment        = $req->contact_payment;
        $email                  = $req->email;
        $rfc                    = $req->rfc;
        $cp                     = $req->cp;
        $expense_account        = $req->expense_account;
        $expense_subaccount     = $req->expense_subaccount;
        $fiscal_account         = $req->fiscal_account;
        $limit                  = $req->limit;
        $status                 = $req->status;
        $user_id                = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                Providers::create([
                    'commercial_business_id' => $commercial_business_id,
                    'name'                   => $name,
                    'business_name'          => $business_name,
                    'adress'                 => $adress,
                    'subrub'                 => $subrub,
                    'state_id'               => $state_id,
                    'municipality_id'        => $municipality_id,
                    'telephone'              => $telephone,
                    'contact_company'        => $contact_company,
                    'contact_payment'        => $contact_payment,
                    'email'                  => $email,
                    'rfc'                    => $rfc,
                    'cp'                     => $cp,
                    'expense_account'        => $expense_account,
                    'expense_subaccount'     => $expense_subaccount,
                    'fiscal_account'         => $fiscal_account,
                    'limit'                  => $limit,
                    'status'                 => $status,
                    'updated_by'             => $user_id,
                    'created_by'             => $user_id
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
                    Providers::where('id',$id)->update([
                        'commercial_business_id' => $commercial_business_id,
                        'name'                   => $name,
                        'business_name'          => $business_name,
                        'adress'                 => $adress,
                        'subrub'                 => $subrub,
                        'state_id'               => $state_id,
                        'municipality_id'        => $municipality_id,
                        'telephone'              => $telephone,
                        'contact_company'        => $contact_company,
                        'contact_payment'        => $contact_payment,
                        'email'                  => $email,
                        'rfc'                    => $rfc,
                        'cp'                     => $cp,
                        'expense_account'        => $expense_account,
                        'expense_subaccount'     => $expense_subaccount,
                        'fiscal_account'         => $fiscal_account,
                        'limit'                  => $limit,
                        'status'                 => $status,
                        'updated_by'             => $user_id
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

    public function deleteProvider(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            Providers::where('id',$id)->update([
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
	
	public function getCfdi(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $cfdis = Cfdi::Leftjoin('users as mod','mod.id','Tbl_Cat_Cfdi.updated_by')
        ->Leftjoin('users as created','created.id','Tbl_Cat_Cfdi.created_by')
        ->select(
        'Tbl_Cat_Cfdi.id',
        'Tbl_Cat_Cfdi.code',
        'Tbl_Cat_Cfdi.description',
        'Tbl_Cat_Cfdi.status',
        'mod.username as updated_by',
        'Tbl_Cat_Cfdi.updated_at',
        'created.username as created_by',
        'Tbl_Cat_Cfdi.created_at')->get();

        if(count($cfdis) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['cfdis'] = $cfdis;

        return $response;
    }

    public function saveCfdi(Request $req){

        $response = ['success' => false ,
        'message' => "No se guardó correctamente"];

        $id                 = $req->id;
        $code               = $req->code;
        $description        = $req->description;
        $status             = $req->status;
        $user_id            = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                Cfdi::create([
                    'code'               => $code,
                    'description'        => $description,
                    'status'             => $status,
                    'updated_by'         => $user_id,
                    'created_by'         => $user_id
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
                    Cfdi::where('id',$id)->update([
                        'code'               => $code,
                        'description'        => $description,
                        'status'             => $status,
                        'status'             => $status,
                        'updated_by'         => $user_id
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
    public function deleteCfdi(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            Cfdi::where('id',$id)->update([
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

    public function getBanks(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $banks = Bank::Leftjoin('users as mod','mod.id','Tbl_Cat_Banks.updated_by')
        ->Leftjoin('users as created','created.id','Tbl_Cat_Banks.created_by')
        ->select(
        'Tbl_Cat_Banks.id',
        'Tbl_Cat_Banks.name',
        'Tbl_Cat_Banks.description',
        'Tbl_Cat_Banks.status',
        'mod.username as updated_by',
        'Tbl_Cat_Banks.updated_at',
        'created.username as created_by',
        'Tbl_Cat_Banks.created_at')->get();

        if(count($banks) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['banks'] = $banks;

        return $response;
    }

    public function saveBank(Request $req){

        $response = ['success' => false ,
        'message' => "No se guardó correctamente"];

        $id                 = $req->id;
        $name               = $req->name;
        $description        = $req->description;
        $status             = $req->status;
        $user_id            = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                Bank::create([
                    'name'               => $name,
                    'description'        => $description,
                    'status'             => $status,
                    'updated_by'         => $user_id,
                    'created_by'         => $user_id
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
                    Bank::where('id',$id)->update([
                        'name'               => $name,
                        'description'        => $description,
                        'status'             => $status,
                        'status'             => $status,
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
    public function deleteBank(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            Bank::where('id',$id)->update([
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
    public function getKindOfPersons(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $kind_of_persons = KindOfPerson::Leftjoin('users as mod','mod.id','Tbl_Cat_KindOfPersons.updated_by')
        ->Leftjoin('users as created','created.id','Tbl_Cat_KindOfPersons.created_by')
        ->select(
        'Tbl_Cat_KindOfPersons.id',
        'Tbl_Cat_KindOfPersons.name',
        'Tbl_Cat_KindOfPersons.description',
        'Tbl_Cat_KindOfPersons.status',
        'mod.username as updated_by',
        'Tbl_Cat_KindOfPersons.updated_at',
        'created.username as created_by',
        'Tbl_Cat_KindOfPersons.created_at')->get();

        if(count($kind_of_persons) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['kind_of_persons'] = $kind_of_persons;

        return $response;
    }

    public function saveKindOfPerson(Request $req){

        $response = ['success' => false ,
        'message' => "No se guardó correctamente"];

        $id                 = $req->id;
        $name               = $req->name;
        $description        = $req->description;
        $status             = $req->status;
        $user_id            = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                KindOfPerson::create([
                    'name'               => $name,
                    'description'        => $description,
                    'status'             => $status,
                    'updated_by'         => $user_id,
                    'created_by'         => $user_id
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
                    KindOfPerson::where('id',$id)->update([
                        'name'               => $name,
                        'description'        => $description,
                        'status'             => $status,
                        'status'             => $status,
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
    public function deleteKindOfPerson(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            KindOfPerson::where('id',$id)->update([
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
	
	public function getCommercialBusiness(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $commercial_business = CommercialBusiness::Leftjoin('users as mod','mod.id','Tbl_Cat_Commercial_business.updated_by')
        ->Leftjoin('users as created','created.id','Tbl_Cat_Commercial_business.created_by')
        ->select(
        'Tbl_Cat_Commercial_business.id',
        'Tbl_Cat_Commercial_business.name',
        'Tbl_Cat_Commercial_business.description',
        'Tbl_Cat_Commercial_business.status',
        'mod.username as updated_by',
        'Tbl_Cat_Commercial_business.updated_at',
        'created.username as created_by',
        'Tbl_Cat_Commercial_business.created_at')->get();

        if(count($commercial_business) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['commercial_business'] = $commercial_business;

        return $response;
    }

    public function saveCommercialBusiness(Request $req){

        $response = ['success' => false ,
        'message' => "No se guardó correctamente"];

        $id                 = $req->id;
        $name               = $req->name;
        $description        = $req->description;
        $status             = $req->status;
        $user_id            = $req->user_id;

        if($id == null || $id == 'undefined' || $id == ''){
            try {
                CommercialBusiness::create([
                    'name'               => $name,
                    'description'        => $description,
                    'status'             => $status,
                    'updated_by'         => $user_id,
                    'created_by'         => $user_id
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
                    CommercialBusiness::where('id',$id)->update([
                        'name'               => $name,
                        'description'        => $description,
                        'status'             => $status,
                        'status'             => $status,
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
    public function deleteCommercialBusiness(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            CommercialBusiness::where('id',$id)->update([
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
