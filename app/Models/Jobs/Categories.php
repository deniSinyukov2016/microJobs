<?php

namespace App\Models\Jobs;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Categories subcategories
 * @property Categories parent
 */

class Categories extends Model
{
    //TODO this code was added
    protected $guarded = ['id'];

    function jobs(){
        return $this->belongsToMany(\App\Models\Jobs\Jobs::class,'job_categories','category_id','job_id','id');
    }
    function openJobs(){
        return $this->belongsToMany(\App\Models\Jobs\Jobs::class,'job_categories','category_id','job_id','id')->where('status','open');
    }

    //TODO this code was added
    public function subcategories()
    {
        return $this->hasMany(static::class, 'parent_id');
    }
    //TODO this code was added
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }


}
