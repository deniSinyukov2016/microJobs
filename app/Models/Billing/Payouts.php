<?php

namespace App\Models\Billing;

use App\Models\Jobs\Applications;
use App\Models\Jobs\Jobs;
use Illuminate\Database\Eloquent\Model;

class Payouts extends Model
{
    //
     protected $table='payouts';
     protected $fillable=['pay_method','txn_id','amount','currency','item_name','item_number','user_id','payment_email','paypal_verify_sign','application_id'];

     function getFormattedDateAttribute(){
         return date('d M, Y',strtotime($this->attributes['created_at']));
     }

     function user(){
         return $this->belongsTo(\App\User::class,'user_id','id');
     }
     function application(){
         return $this->belongsTo(\App\Models\Jobs\Applications::class,'application_id','id');
     }

}
