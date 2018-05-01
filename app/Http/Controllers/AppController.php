<?php

namespace App\Http\Controllers;

use App\Models\Jobs\Jobs;
use Illuminate\Http\Request;

class AppController extends Controller
{

    function index(){
        if(isset($_GET['job'])){

            $search = $_GET['job'];
            $jobs = Jobs::orderBy('created_at','DESC')
                ->orWhere('id',$search)
                ->orWhere('name','like','%'.$search.'%')
                ->orWhere('desc','like','%'.$search.'%')
                ->where('status','open')->paginate(20);
        }else{
            $jobs = Jobs::orderBy('created_at','DESC')->where('status','open')->paginate(20);

        }
        return view('home',compact('jobs'));
    }

}
