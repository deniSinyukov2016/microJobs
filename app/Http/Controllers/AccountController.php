<?php

namespace App\Http\Controllers;

use App\Models\Billing\Billing;
use App\Models\User\UserSkills;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:update-profile',['only'=>['updateProfile','updateBio']]);
        $this->middleware('permission:read-profile',['only'=>['index']]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function index()
    {
        $user = Auth::user();
        return view('auth.account', compact('user'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function updateProfile(Request $request)
    {
        $id = Auth::user()->id;

        $rules = [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users,email,' . $id,
        ];

        if (Input::has('password')) {
            $rules2 = [
                'password' => 'min:6|confirmed',
                'password_confirmation' => 'required|min:6'
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
        $user->stripe_public_key=$request->stripe_public_key;
        if($request->has('stripe_secret_key'))
            $user->stripe_secret_key = encrypt($request->stripe_secret_key);
        $user->save();

        flash()->success('Profile updated!');
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function updateBio(Request $request)
    {
        $rules = [
            'bio' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user = User::find(Auth::user()->id);
        $user->bio = $request->bio;
        $user->save();

        //update skills
        if ($request->has('skills')) {
            $skills = explode(',', $request->skills);
            foreach ($skills as $skill) {
                UserSkills::firstOrCreate(['user_id' => Auth::user()->id, 'skill_id' => $skill]);
            }
        }
        flash()->success('You profile has been updated!');
        return redirect()->back();
    }

}
