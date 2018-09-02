<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegmentationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('segmentations', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('device');
            $table->string('country');
            $table->timestamps();
        });

        Schema::table('segmentations', function (Blueprint $table){
            $table->bigInteger('campaingn_id')->unsigned();
            $table->foreign('campaingn_id')->references('id')->on('campaingns')
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
        Schema::dropIfExists('segmentations');
    }
}
