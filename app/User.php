<?php

namespace App;

use App\Models\Jobs\Bookmarks;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','stripe_public_key','stripe_secret_key'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','stripe_secret_key'
    ];

    function bookmarks(){
        return $this->hasMany(Bookmarks::class,'user_id','id');
    }
    function bookmarked(){
        return $this->hasMany(\App\Models\Jobs\Jobs::class,'job_id','id');
    }
    function applications(){
        return $this->hasMany(\App\Models\Jobs\Applications::class,'user_id','id');
    }
    function skills(){
        return $this->belongsToMany(\App\Models\Jobs\Skills::class,'user_skills','user_id','skill_id','id');
    }

    function appliedJobs(){
        return $this->belongsToMany(\App\Models\Jobs\Jobs::class,'applications','user_id','job_id','id')->where('jobs.deleted_at','=',null);
    }
    function role(){
        return $this->belongsToMany(\App\Models\RoleUser::class,'role_user','role_id','user_id','id');
    }

    function getRegisteredOnAttribute(){
        return date('d M, Y',strtotime($this->attributes['created_at']));
    }

    function stripe(){
        return $this->belongsTo(\App\Models\Billing\Stripe::class);
    }

    function getStripeSecretKeyAttribute($value){
        if($value==null){
            return '';
        }
        try {
            return decrypt($value);
        } catch (DecryptException $e) {
            return '';
        }
    }
}
