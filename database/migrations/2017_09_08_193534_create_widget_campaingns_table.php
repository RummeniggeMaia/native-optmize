<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetCampaingnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_campaingns', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->timestamps();
        });
		
		Schema::table('widget_campaingns', function(Blueprint $table) {
			$table->bigInteger('related_widget')->unsigned();
			$table->bigInteger('related_campaingn')->unsigned();
			$table->foreign('related_widget')->references('id')->on('widgets');
			$table->foreign('related_campaingn')->references('id')->on('campaingns');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget_campaingns');
    }
}
