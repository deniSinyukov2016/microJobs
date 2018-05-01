<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-users',['only'=>['users']]);
        $this->middleware('permission:update-users',['only'=>['user','updateUser','updateUserRole']]);
        $this->middleware('permission:create-users',['only'=>['register']]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function users()
    {
        $users = User::paginate(20);
        return view('admin.users', compact('users'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function user($id)
    {

        $user = User::find($id);
        $roles = Role::all();
        $currentRole = $user->roles->first();
        if ($currentRole == null)
            $currentRole = 0;
        else
            $currentRole =$currentRole->id;

        return view('admin.user', compact('user', 'roles', 'currentRole'));
    }

    /**
     * for admin and account owners
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function register(Request $request)
    {

        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
        ];
        if (Input::has('password')) {
            $rules2 = [
                'password' => 'min:6|confirmed'
            ];
            $rules = array_collapse([$rules, $rules2]);
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->confirmation_code = str_random(28);
        $user->created_at = date('Y-m-d H:i:s');
        $user->save();

        //assign role
        $user = User::find($user->id);
        $user->roles()->attach(env('DEFAULT_ROLE'));

        //send registration notice
        flash()->success('User has been registered and confirmation email sent.');
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    function updateUser(Request $request, $id)
    {


        $rules = [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users,email,' . $id
        ];

        if (Input::has('password')) {
            $rules2 = [
                'password' => 'min:6|confirmed'
            ];
            $rules = array_collapse([$rules, $rules2]);
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::find($id);

        if (Input::has('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->email = $request->email;
        $user->name = $request->name;
        $user->updated_at = date('Y-m-d H:i:s');
        $user->save();

        flash()->success('User information updated!');
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function updateUserRoles(Request $request, $id)
    {


        $user = User::findOrFail($id);
        //delete all
        //todo prevent admin self delete
        if (Auth::user()->hasRole(['admin', 'manager']) && $id == Auth::user()->id) {
            flash()->error('You cannot remove your own rights');
            return redirect()->back();
        } else {
            DB::table('role_user')->whereUserId($request->id)->delete();
        }
        //reassign
        $user->roles()->attach($request->role);

        flash()->success('Roles updated');
        return redirect()->back();
    }

}
