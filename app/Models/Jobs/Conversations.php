<?php

namespace App\Models\Jobs;

use Illuminate\Database\Eloquent\Model;

class Conversations extends Model
{
    protected $fillable = ['application_id','user_id','message'];
}
