<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_logs', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->timestamps();
            $table->integer('clicks')->default(0);
            $table->integer('impressions')->default(0);
            $table->double('revenues')->default(0);
        });

        Schema::table('campaign_logs', function(Blueprint $table) {
            $table->bigInteger('campaingn_id')->unsigned()->nullable();
            $table->foreign('campaingn_id')->references('id')
                    ->on('campaingns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_logs');
    }
}
