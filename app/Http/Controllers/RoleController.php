<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RolePermission;
use App\Models\RoleSetting;
use App\Models\Role;
use App\Models\Module;

class RoleController extends Controller
{

    public function index()
    {
    }

    public function save(Request $req)
    {
        Role::updateOrCreate($req->all());

        Operation::create(['user_id' => $req->filled('createdBy') ? $req->createdBy : 0, 'action' => 'Save Role', 'description' => json_encode($req->all())]);
        
        return response()->json(['error' => false, 'message' => 'Changes saved']);
    }

    public static function getByRole($id)
    {
        return RolePermission::where('roleId', $id)->get()->map(function($permission){
            $permission->modules = array_map('intval', explode(',', $permission->modules));

            return $permission;
        })->first();
    }

    public static function getByUser(Request $req)
    {
        $modules = collect();

         RolePermission::where('user_id', $req->userId)->get()->map(function($permission) use ($modules) {
            $modules->push($permission->module_id);

        });

        return $modules;
    }

    public static function getEditByUser(Request $req)
    {
        $modules = collect();

         RolePermission::where('user_id', $req->userId)->where('edit', true)->get()->map(function($permission) use ($modules) {
            $modules->push($permission->module_id);

        });

        return $modules;
    }

    public static function getDeleteByUser(Request $req)
    {
        $modules = collect();

         RolePermission::where('user_id', $req->userId)->where('delete', true)->get()->map(function($permission) use ($modules) {
            $modules->push($permission->module_id);

        });

        return $modules;
    }

    public static function getByUserAndRole($user)
    {
        if($user->role_id == 0){
            return Module::get()->map(function($module) {
                $module->module_id = $module->id;

                return $module;
            });
        }
        $rolePermissions = RolePermission::join('modules as mod','role_permissions.module_id','mod.id')
                                            ->whereRaw('user_id = ' . ($user->role_id == 0 ? 'user_id' : $user->id))
                                            ->whereRaw('role_id = ' . ($user->role_id == 0 ? 'role_id' : $user->role_id))
                                            ->where('mod.deleted',0)
                                            ->orderBy('mod.name')
                                            ->get();
        $rolePermissionsDefault = RoleSetting::join('modules as mod','role_settings.module_id','mod.id')
                                                ->whereRaw('role_id = ' . ($user->role_id == 0 ? 'role_id' : $user->role_id))
                                                ->where('mod.deleted',0)
                                                ->orderBy('mod.name')
                                                ->get();

        return $rolePermissions->merge($rolePermissionsDefault);
        
    }

    /*return Module::where('deleted', false)->where('status', true)->get()->map(function($menu) {
            $menu['roles'] = RoleSetting::where('module_id', $menu->id)->get()->map(function($role) {
                return $role->role_id;
            });

            return $menu;
        });*/

}
