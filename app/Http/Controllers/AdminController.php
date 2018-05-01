<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin',['only'=>['settings','updateEnv','backupEnv']]);
    }

    /**
     * @return View
     */
    function settings()
    {

        $roles = Role::all();

        $envFile = "../.env";
        $fhandle = fopen($envFile, "rw");
        $size = filesize($envFile);
        $envContent ="";
        if($size ==0){
            flash()->error('Your .env file is empty');
        }else{
            $envContent = fread($fhandle,$size);
            fclose($fhandle);
        }

        return view('admin.settings', compact('envContent','roles'));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    function backupEnv(Request $request){

        $envFile = "../.env";
        return response()->download($envFile, env('APP_NAME').'-ENV-'.date('Y-m-d_H-i').'.txt');
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function updateEnv(Request $request)
    {
        $rules = [
            'envContent' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $envFile = "../.env";
        $fhandle = fopen($envFile, "w");
        fwrite($fhandle, trim($request->envContent," \t\0\x0B"));
        fclose($fhandle);
        flash()->success('Settings have been update. Please verify that your application is working properly.');
        return redirect()->back();
    }


    /**
     * @return View
     */
    function logs(){
        $logFile = "../.syslogs";
        $logContent ="";
        if(is_file($logFile)){
            $fhandle = fopen($logFile, "rw");
            $size = filesize($logFile);
            if($size ==0){
                flash()->error('Your log file is empty');
            }else{
                $logContent = fread($fhandle,$size);
                fclose($fhandle);
            }

        }else{
            flash()->error('Your log file is empty');
        }
        return view('admin.logs', compact('logContent'));

    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    function emptyLog(){

        $logFile = "../.syslogs";
        $fhandle = fopen($logFile, "w");
        fwrite($fhandle,'');
        fclose($fhandle);
        flash()->success('Degub log has been emptied');
        return redirect()->back();
    }

    /**
     * @return View
     */
    function debug(){

        $logFile = "../storage/logs/laravel.log";
        $fhandle = fopen($logFile, "rw");
        $size = filesize($logFile);
        $logContent ="";
        if($size ==0){
            flash()->error('Your log file is empty');
        }else{
            $logContent = fread($fhandle,$size);
            fclose($fhandle);
        }

        return view('admin.debug-log', compact('logContent'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function emptyDebugLog(){

        $logFile = "../storage/logs/laravel.log";
        $fhandle = fopen($logFile, "w");
        fwrite($fhandle,'');
        fclose($fhandle);
        flash()->success('Debug log has been emptied');
        return redirect()->back();
    }

}
