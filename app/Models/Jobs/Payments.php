<?php

namespace App\Models\Jobs;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $fillable=[];
    function job(){
        return $this->belongsTo(\App\Models\Jobs\Jobs::class,'job_id','id');
    }
    function user(){
        return $this->belongsTo(\App\User::class,'user_id','id');
    }
}
