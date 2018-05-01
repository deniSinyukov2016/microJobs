<?php

namespace App\Models\Jobs;

use Illuminate\Database\Eloquent\Model;

class DifficultyLevel extends Model
{
    protected $table='difficulty_level';

    function jobs(){
        return $this->hasMany(\App\Models\Jobs\Jobs::class);
    }
}
