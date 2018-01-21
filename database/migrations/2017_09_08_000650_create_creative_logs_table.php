<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreativeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creative_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('click_id')->nullable();
            $table->double('revenue')->default(0.0);
        });
        Schema::table('creative_logs', function(Blueprint $table) {
            $table->bigInteger('creative_id')->unsigned();
            $table->foreign('creative_id')->references('id')->on('creatives');
            $table->bigInteger('widget_id')->unsigned();
            $table->foreign('widget_id')->references('id')->on('widgets');
            $table->bigInteger('campaingn_id')->unsigned();
            $table->foreign('campaingn_id')->references('id')->on('campaingns');

            $table->bigInteger('owner')->unsigned();
            $table->foreign('owner')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creative_logs');
    }
}
