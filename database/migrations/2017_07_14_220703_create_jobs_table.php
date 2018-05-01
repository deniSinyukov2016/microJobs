<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50);
            $table->text('desc');
            $table->longText('instructions')->nullable();
            $table->date('end_date');
            $table->integer('user_id',false,true)->nullable();
            $table->float('price')->default('0.00');
            $table->integer('difficulty_level_id',false,true)->nullable();
            $table->string('status',10)->default('open'); //open, closed
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('difficulty_level_id')->references('id')->on('difficulty_level');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
