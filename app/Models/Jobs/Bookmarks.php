<?php

namespace App\Models\Jobs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bookmarks extends Model
{

    function user(){
        return $this->belongsTo(\App\User::class,'user_id','id');
    }

    function job(){
        return $this->belongsTo(\App\Models\Jobs\Jobs::class,'job_id','id');
    }
}
