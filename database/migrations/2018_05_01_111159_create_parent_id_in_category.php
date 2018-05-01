<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParentIdInCategory extends Migration
{
    //TODO this code was added
    public function up()
    {
        Schema::table('categories', function(Blueprint $table)
        {
            $table->integer('parent_id', false, true)->nullable()->after('cat_order');
        });
    }

    public function down()
    {
        Schema::table('categories', function(Blueprint $table){
            $table->dropColumn('parent_id');
        });
    }
}
