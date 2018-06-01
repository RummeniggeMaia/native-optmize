<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_datas', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('paypal')->nullable();
            $table->string('number');
            $table->string('agency');
            $table->integer('type');
            $table->string('bank');
            $table->string('bank_number');
            $table->string('cpf');
            $table->string('holder');
            $table->timestamps();
        });

        Schema::table('payment_datas', function(Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_datas');
    }
}
