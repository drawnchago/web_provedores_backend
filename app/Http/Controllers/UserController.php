<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\RoleController;
use App\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        //
    }

    public function login(Request $req){

        $response = [
            'success' => true
        ];

        $user = User::where('username', $req->username)->where('password',$req->password)->where('status',1)->first();

        if($user){
            $user->role = Role::find($user->role_id);
            $user->rolePermissions = RoleController::getByUserAndRole($user);
            $response['user'] = $user;
        }else{
            $response['success'] = false;
            $response['message'] = 'Usuario o contraseña incorrectaos';
        }
        return response()->json($response);
    }

    public function getAll(){
        $response = ['success' => true];

        $users = User::where('deleted',false)->whereNotIn('role_id',[0])->get()->map(function ($user){
            $user->role = Role::find($user->role_id);

            return $user;
        });

        $response['data'] = $users;

        return response()->json($response);
    }
    
}
