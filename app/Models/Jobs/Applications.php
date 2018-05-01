<?php

namespace App\Models\Jobs;

use App\Mail\NotifyUserAppStatus;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Applications extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function job()
    {
        return $this->belongsTo(\App\Models\Jobs\Jobs::class, 'job_id', 'id');
    }
    function messages(){
        return $this->hasMany(\App\Models\Jobs\Conversations::class,'application_id','id');
    }

    /**
     * @param $value
     * @return false|string
     */
    function getCreatedAtAttribute($value)
    {
        return date('d M, Y', strtotime($value));
    }

    /**
     * @param $value
     * @return false|string
     */
    function getDeadlineAttribute($value)
    {
        if ($value == null) {
            return "Not Specified";
        }
        return date('d M, Y', strtotime($value));
    }
    /**
     * add currency symbol
     * @return string
     */
    function getFormattedPriceAttribute(){
        $price = $this->attributes['job_price'];
        return env('CURRENCY_SYMBOL') .$price;
    }

    /**
     * @return string
     */
    function getPrettyStatusAttribute()
    {
        $status = strtolower($this->attributes['status']);
        //pending, approved,denied, closed
        switch ($status) {
            case "pending":
                $color = "warning";
                break;
            case "approved":
                $color = "success";
                break;
            case "denied":
                $color = "danger";
                break;
            case "closed":
                $color = "default";
                break;
            default:
                $color = "default";
                break;
        }
        return '<span class="label label-' . $color . '">' . ucwords($status) . '</span>';
    }

    /**
     * @param $app
     */
    function notifyOnChangeStatus($app){
        //if approved, notify user
        //if denied, tell them of status change
        $job = Jobs::find($app->job_id);
        $applicant = User::find($app->user_id);
        Mail::to($applicant->email, $applicant->name)->send(new NotifyUserAppStatus($job, $app, $applicant));
    }
}
