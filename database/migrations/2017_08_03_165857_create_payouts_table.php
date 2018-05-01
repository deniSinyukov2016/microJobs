<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id',false,true);
            $table->string('pay_method',20); //paypal, stripe
            $table->float('amount');
            $table->string('txn_id',50)->unique();
            $table->char('currency',3);
            $table->string('item_name',100);
            $table->string('item_number',20)->nullable();
            $table->integer('user_id',false,true)->nullable();
            $table->string('payment_email',50)->nullable();
            $table->string('paypal_verify_sign')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payouts');
    }
}
