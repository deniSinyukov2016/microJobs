<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserSkills extends Model
{
    protected $fillable = [
        'user_id', 'skill_id',
    ];
}
