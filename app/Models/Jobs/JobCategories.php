<?php

namespace App\Models\Jobs;

use Illuminate\Database\Eloquent\Model;

class JobCategories extends Model
{
    /**
     * @var array
     */
    protected $fillable=['category_id','job_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function jobs(){
        return $this->belongsTo(\App\Models\Jobs\Jobs::class,'job_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    function categories(){
        return $this->belongsToMany(\App\Models\Jobs\Categories::class,'categor_id','id');
    }
}
