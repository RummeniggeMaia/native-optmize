<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaingnCreativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaingn_creatives', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->timestamps();
        });
		
		Schema::table('campaingn_creatives', function(Blueprint $table) {
			$table->bigInteger('related_campaingn')->unsigned();
			$table->bigInteger('target_creative')->unsigned();
			$table->foreign('related_campaingn')->references('id')->on('campaingns');
			$table->foreign('target_creative')->references('id')->on('creatives');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaingn_creatives');
    }
}
