<?php

namespace App\Queries;

use App\Models\Jobs\Categories;

class CategoryQuery
{
    //TODO this code was added
    public static function onlyParent()
    {
        return Categories::query()->whereNull('parent_id');
    }
}