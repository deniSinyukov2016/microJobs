<?php

namespace App\Http\Controllers\Auth;

use App\Models\Modules;
use App\Models\PermissionRole;
use App\Models\RoleUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Permission;
use App\Role;

class AuthController extends Controller
{

    /**
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin',['only'=>['permissions','updateRolePermissions','roles','showRole','newRole','updateRole']]);
    }


    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    function getLogout()
    {
        Auth::logout();
        return redirect('/');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function permissions($role_id, $module_id)
    {

        if (request()->ajax()) {
            $module = Modules::find($module_id);
            $levels = ['create', 'read', 'update', 'delete'];
            $rolePerms = array();
            foreach ($levels as $level) {
                $perm = Permission::where('name', $level . '-' . $module->name)->first();
                $role = null;
                if (count($perm) > 0)
                    $role = DB::table('permission_role')->where('role_id', $role_id)->where('permission_id', $perm->id)->first();
                if ($role == null)
                    $selected = false;
                else
                    $selected = true;
                $rolePerms[] = array(
                    'selected' => $selected,
                    'level' => $level
                );
            }
            return view('admin.permissions', compact('rolePerms'));
        }
    }

    /**
     * @param Request $request
     */
    function updateRolePermissions(Request $request)
    {

        if ($request->ajax()) {
            $role = $request->role;
            $module = Modules::find($request->module);
            $perms = $request->permissions;
            if (is_array($perms)) {
                //flush all permissions for this module
                $ps = ['create', 'update', 'read', 'delete'];
                foreach ($ps as $p) {
                    $permission = $p . '-' . $module->name;
                    $res = Permission::firstOrCreate(['name' => $permission, 'display_name' => ucwords($p) . ' ' . ucwords($module->name)]);
                    PermissionRole::where('permission_id', $res->id)->where('role_id', $role)->delete();
                }
                //assign new
                foreach ($perms as $perm) {
                    $permission = $perm . '-' . $module->name;
                    //find the permission
                    $p = Permission::where('name', $permission)->first();
                    DB::table('permission_role')->insert([
                        'permission_id' => $p->id,
                        'role_id' => $role
                    ]);
                }
            } else {
                DB::table('permission_role')->where('role_id', $role)->delete();
            }

            echo json_encode(['status' => 'success', 'message' => 'Role permissions updated']);
        }

    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function roles()
    {
        $roles = Role::all();
        $modules = Modules::all();
        return view('admin.roles', compact('roles', 'modules'));
    }

    function showRole(Request $request)
    {
        if ($request->ajax()) {
            $role = Role::find($request->role_id);
            $data = array(
                'name' => $role->name,
                'display_name' => $role->display_name,
                'desc' => $role->desc
            );
            return $data;
        }
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function newRole(Request $request)
    {

        $rules = [
            'name' => 'required|max:50|unique:roles',
            'display_name' => 'required|max:50|unique:roles'

        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $request->name = str_clean($request->name);
        Role::create($request->all());

        flash()->success('Role added');
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    function updateRole(Request $request,$id){

        $rules = [
            'display_name' => 'required|max:50|unique:roles,display_name,'.$id
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $role = Role::find($id);

        $role->fill($request->all());
        $role->save();

        flash()->success('Role updated!');
        return redirect()->back();
    }

}