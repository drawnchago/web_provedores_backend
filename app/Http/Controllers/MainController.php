<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\RoleController;
use App\Models\Role;
use App\Models\RoleSetting;
use App\Models\Module;
use App\Models\Municipality;
use App\Models\State;
use App\Models\Areas;
use App\Models\Products;
use App\Models\BranchOffices;
use App\Models\Customers;

class MainController extends Controller
{
    public function __construct()
    {
        //
    }

    public function getMenu2() {   
        $items = [
            [
                'id' => 1,
                'state'=> 'dashboard',
                'name'=> 'Dashboard',
                'type'=> 'link',
                'icon'=> 'av_timer',
            ],
            [
                'id' => 2,
                'state'=> 'widgets',
                'name'=> 'Widgets',
                'type'=> 'link',
                'icon'=> 'widgets'
            ],
            [
                'id' => 3,
                'state'=> 'multi',
                'name'=> 'Menu Levels',
                'icon'=> 'star',
                'type'=> 'sub',
                'children'=> [
                    [
                        'id' => 4,
                        'state'=> 'second-level',
                        'name'=> 'Second Level',
                        'type'=> 'link'
                    ],
                    [
                        'id' => 5,
                        'state'=> 'third-level',
                        'name'=> 'Second Level',
                        'type'=> 'subchild',
                        'subchildren'=> [
                            [
                                'id' => 6,
                                'state'=> 'third-level',
                                'name'=> 'Third Level',
                                'type'=> 'link'
                            ]
                        ]
                    ],
                    [
                        'id' => 7,
                        'state'=> 'third-level',
                        'name'=> 'aSecond Level',
                        'type'=> 'subchild',
                        'subchildren'=> [
                            [
                                'id' => 8,
                                'state'=> 'athird-level',
                                'name'=> 'Third Level',
                                'type'=> 'link'
                            ]
                        ]
                    ]
                ]
                            ],
        ];

        return response()->json($items);
        
    }

    public function getMenu(Request $req){
        return Module::where('deleted', false)->where('status', true)->where('parentId',0)->orderBy('position')->get()->map(function($menu) {
            if($menu->type == 'sub'){
                $menu['children'] = Module::where('deleted', false)->where('status', true)->where('parentId',$menu->id)->orderBy('position')->get()->map(function($child) {
                    if($child->type == 'subchild'){
                        $child['subchildren'] = Module::where('deleted', false)->where('status', true)->where('parentId',$child->id)->orderBy('position')->get();
                    }

                    return $child;
                });
            }

            return $menu;
        });
    }
    
    public function getCustomersByType(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $type      = $req->type;
        $customers = [];

        switch($type){
            case 0:
                // OBTIENE CLIENTES POR ID
                $id = $req->id;
                $customers = Customers::Leftjoin('users as mod','mod.id','Tbl_Op_Customers.updated_by')->Leftjoin('users as created','created.id','Tbl_Op_Customers.created_by')->where('Tbl_Op_Customers.id',$id)
                ->select('Tbl_Op_Customers.id','Tbl_Op_Customers.name','Tbl_Op_Customers.description','Tbl_Op_Customers.status','mod.username as updated_by','Tbl_Op_Customers.updated_at','created.username as created_by','Tbl_Op_Customers.created_at')->get();
                break;

            case 1:
                //OBTIENES TODOS LOS CLIENTES ACTIVOS
                $customers = Customers::Leftjoin('users as mod','mod.id','Tbl_Op_Customers.updated_by')->Leftjoin('users as created','created.id','Tbl_Op_Customers.created_by')->where('Tbl_Op_Customers.status',1)
                ->select('Tbl_Op_Customers.id','Tbl_Op_Customers.name','Tbl_Op_Customers.description','Tbl_Op_Customers.status','mod.username as updated_by','Tbl_Op_Customers.updated_at','created.username as created_by','Tbl_Op_Customers.created_at')->get();
                break;

            case 2:
                //OBTIENES TODOS LOS CLIENTES ACTIVOS/INACTIVOS
                $customers = Customers::Leftjoin('users as mod','mod.id','Tbl_Op_Customers.updated_by')->Leftjoin('users as created','created.id','Tbl_Op_Customers.created_by')
                ->select('Tbl_Op_Customers.id','Tbl_Op_Customers.name','Tbl_Op_Customers.description','Tbl_Op_Customers.status','mod.username as updated_by','Tbl_Op_Customers.updated_at','created.username as created_by','Tbl_Op_Customers.created_at')->get();
                break;
            
        }

        $response['customers'] = $customers;

        if(count($customers) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        return $response;
    }

    public function getStatesByType(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $type      = $req->type;
        $countries = [];

        switch($type){
            case 0:
                // OBTIENE EL ESTADO POR ID
                $id     = $req->id;
                $states = State::Leftjoin('users as mod','mod.id','Tbl_Cat_States.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_States.created_by')->where('Tbl_Cat_States.id',$id)
                ->select('Tbl_Cat_States.id','Tbl_Cat_States.name','Tbl_Cat_States.status','mod.username as updated_by','Tbl_Cat_States.updated_at','created.username as created_by','Tbl_Cat_States.created_at')->get();
                break;

            case 1:
                //OBTIENES TODOS LOS ESTADOS ACTIVOS
                $states = State::Leftjoin('users as mod','mod.id','Tbl_Cat_States.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_States.created_by')->where('Tbl_Cat_States.status',1)
                ->select('Tbl_Cat_States.id','Tbl_Cat_States.name','Tbl_Cat_States.status','mod.username as updated_by','Tbl_Cat_States.updated_at','created.username as created_by','Tbl_Cat_States.created_at')->get();
                break;

            case 2:
                //OBTIENES TODOS LOS ESTADOS ACTIVOS/INACTIVOS
                $states = State::Leftjoin('users as mod','mod.id','Tbl_Cat_States.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_States.created_by')
                ->select('Tbl_Cat_States.id','Tbl_Cat_States.name','Tbl_Cat_States.status','mod.username as updated_by','Tbl_Cat_States.updated_at','created.username as created_by','Tbl_Cat_States.created_at')->get();
                break;
        }

        $response['states'] = $states;

        if(count($states) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        return $response;
    }
    public function getMunicipalitiesByType(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $type           = $req->type;
        $municipalities = [];

        switch($type){
            case 0:
                // OBTIENE EL MUNICIPIO POR ID
                $id = $req->id;
                $municipalities = Municipality::Leftjoin('users as mod','mod.id','Tbl_Cat_Municipalities.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_Municipalities.created_by')->where('Tbl_Cat_Municipalities.id',$id)
                ->select('Tbl_Cat_Municipalities.id','Tbl_Cat_Municipalities.name','Tbl_Cat_Municipalities.status','mod.username as updated_by','Tbl_Cat_Municipalities.updated_at','created.username as created_by','Tbl_Cat_Municipalities.created_at')->get();
                break;

            case 1:
                //OBTIENES TODOS LOS MUNICIPIO ACTIVOS
                $municipalities = Municipality::Leftjoin('users as mod','mod.id','Tbl_Cat_Municipalities.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_Municipalities.created_by')->where('Tbl_Cat_Municipalities.status',1)
                ->where('Tbl_Cat_Municipalities.state_id',$req->id)
                ->select('Tbl_Cat_Municipalities.id','Tbl_Cat_Municipalities.name','Tbl_Cat_Municipalities.status','mod.username as updated_by','Tbl_Cat_Municipalities.updated_at','created.username as created_by','Tbl_Cat_Municipalities.created_at')->get();
                break;

            case 2:
                //OBTIENES TODOS LOS MUNICIPIO ACTIVOS/INACTIVOS
                $municipalities = Municipality::Leftjoin('users as mod','mod.id','Tbl_Cat_Municipalities.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_Municipalities.created_by')
                ->select('Tbl_Cat_Municipalities.id','Tbl_Cat_Municipalities.name','Tbl_Cat_Municipalities.status','mod.username as updated_by','Tbl_Cat_Municipalities.updated_at','created.username as created_by','Tbl_Cat_Municipalities.created_at')->get();
                break;
        }

        $response['municipalities'] = $municipalities;

        if(count($municipalities) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        return $response;
    }

    public function getAreasByType(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $id    = $req->id;
        $type  = $req->type;
        $areas = [];

        switch($type){
            case 0:
                // OBTIENE EL ESTADO POR ID
                $id = $req->id;
                $areas   = Areas::Leftjoin('users as mod','mod.id','Tbl_Cat_Areas.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_Areas.created_by')->where('Tbl_Cat_Areas.id',$id)
                ->select('Tbl_Cat_Areas.id','Tbl_Cat_Areas.description','Tbl_Cat_Areas.status','mod.username as updated_by','Tbl_Cat_Areas.updated_at','created.username as created_by','Tbl_Cat_Areas.created_at')->get();
                break;

            case 1:
                //OBTIENES TODOS LOS ESTADOS ACTIVOS
                $areas   = Areas::Leftjoin('users as mod','mod.id','Tbl_Cat_Areas.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_Areas.created_by')->where('Tbl_Cat_Areas.status',1)
                ->select('Tbl_Cat_Areas.id','Tbl_Cat_Areas.description','Tbl_Cat_Areas.status','mod.username as updated_by','Tbl_Cat_Areas.updated_at','created.username as created_by','Tbl_Cat_Areas.created_at')->get();
                break;

            case 2:
                //OBTIENES TODOS LOS ESTADOS ACTIVOS/INACTIVOS
                $areas   = Areas::Leftjoin('users as mod','mod.id','Tbl_Cat_Areas.updated_by')->Leftjoin('users as created','created.id','Tbl_Cat_Areas.created_by')
                ->select('Tbl_Cat_Areas.id','Tbl_Cat_Areas.description','Tbl_Cat_Areas.status','mod.username as updated_by','Tbl_Cat_Areas.updated_at','created.username as created_by','Tbl_Cat_Areas.created_at')->get();
                break;
        }

        $response['areas'] = $areas;

        if(count($areas) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }


        return $response;
    }

    public function getUsersByType(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $id    = $req->id;
        $type  = $req->type;
        $users = [];

        switch($type){
            case 0:
                // OBTIENE EL ESTADO POR ID
                $id = $req->id;
                $users   = User::where('id',$id)->get();
                break;

            case 1:
                //OBTIENES TODOS LOS ESTADOS ACTIVOS
                $users   = User::where('status',1)->get();
                break;

            case 2:
                //OBTIENES TODOS LOS ESTADOS ACTIVOS/INACTIVOS
                $users   = User::get();
                break;
        }

        $response['users'] = $users;

        if(count($users) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }


        return $response;
    }
    public function getProductsByType(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $id          = $req->id;
        $type        = $req->type;
        $description = $req->description;
        $products = [];

        switch($type){
            case 0:
                // OBTIENE EL PRODUCTOS POR ID
                $id = $req->id;
                $products = Products::Leftjoin('users as mod','mod.id','Tbl_Cat_Products.updated_by')
                ->Leftjoin('users as created','created.id','Tbl_Cat_Products.created_by')
                ->Leftjoin('Tbl_Cat_MeasurementUnits','Tbl_Cat_Products.measurement_unit_id','Tbl_Cat_MeasurementUnits.id')
                ->Leftjoin('Tbl_Cat_Classifications','Tbl_Cat_Products.classification_id','Tbl_Cat_Classifications.id')
                ->Leftjoin('Tbl_Cat_TypesOfProducts','Tbl_Cat_Products.type_product_id','Tbl_Cat_TypesOfProducts.id')
                ->where('Tbl_Cat_Products.status',1)
                ->where('Tbl_Cat_Products.id',$id)
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
                'mod.username as updated_by','Tbl_Cat_Products.updated_at','created.username as created_by','Tbl_Cat_Products.created_at')->get();
        
                break;

            case 1:
                //OBTIENES TODOS LOS PRODUCTOS ACTIVOS
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
        
                break;

            case 2:
                //OBTIENES TODOS LOS PRODUCTOS ACTIVOS/INACTIVOS
                $products = Products::Leftjoin('users as mod','mod.id','Tbl_Cat_Products.updated_by')
                ->Leftjoin('users as created','created.id','Tbl_Cat_Products.created_by')
                ->Leftjoin('Tbl_Cat_MeasurementUnits','Tbl_Cat_Products.measurement_unit_id','Tbl_Cat_MeasurementUnits.id')
                ->Leftjoin('Tbl_Cat_Classifications','Tbl_Cat_Products.classification_id','Tbl_Cat_Classifications.id')
                ->Leftjoin('Tbl_Cat_TypesOfProducts','Tbl_Cat_Products.type_product_id','Tbl_Cat_TypesOfProducts.id')
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
        
                break;
            case 3:
                //OBTIENES TODOS LOS PRODUCTOS ACTIVOS POR OPERADOR LIKE
                $products = Products::Leftjoin('users as mod','mod.id','Tbl_Cat_Products.updated_by')
                ->Leftjoin('users as created','created.id','Tbl_Cat_Products.created_by')
                ->Leftjoin('Tbl_Cat_MeasurementUnits','Tbl_Cat_Products.measurement_unit_id','Tbl_Cat_MeasurementUnits.id')
                ->Leftjoin('Tbl_Cat_Classifications','Tbl_Cat_Products.classification_id','Tbl_Cat_Classifications.id')
                ->Leftjoin('Tbl_Cat_TypesOfProducts','Tbl_Cat_Products.type_product_id','Tbl_Cat_TypesOfProducts.id')
                ->where('Tbl_Cat_Products.status',1)
                ->whereRaw("lower(Tbl_Cat_Products.description) like '%$description%'")
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
        
                break;
        }

        $response['products'] = $products;

        if(count($products) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }


        return $response;
    }

    public function getBranchOffices(){
        $response = ['success' => true];

        $branchOffices = BranchOffices::where('status',1)->get();

        if(count($branchOffices) == 0){
            $response['success'] = false;
            $response['message'] = 'No se encontraron Oficinas, favor de dar de alta al menos una';
        }
        $response['info'] = $branchOffices;

        return response()->json($response);
    }

    public function getCustomerByDescription(Request $req){
        $response = ['success' => true];

        $customers = Customers::whereRaw("name like '%$req->filter%'")->get();

        if(count($customers) == 0){
            $response['success'] = false;
            $response['message'] = '';
        }
        $response['info'] = $customers;

        return response()->json($response);
    }

    public function getTasaIVA(){
        $response = ['success' => true, 'info' => .16];

        return response()->json($response);
    }
}
