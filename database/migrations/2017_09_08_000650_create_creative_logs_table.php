<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreativeLogsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('creative_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('clicks')->default(0);
            $table->bigInteger('impressions')->unsigned()->default(0);
            $table->double('revenue')->default(0.0);
            $table->timestamps();
        });
        Schema::table('creative_logs', function(Blueprint $table) {
            $table->bigInteger('creative_id')->unsigned();
            $table->foreign('creative_id')->references('id')->on('creatives')
                    ->onDelete('cascade');
            $table->bigInteger('widget_id')->unsigned()->nullable();
            $table->foreign('widget_id')->references('id')->on('widgets')
                    ->onDelete('set null');
            $table->bigInteger('campaingn_id')->unsigned()->nullable();
            $table->foreign('campaingn_id')->references('id')->on('campaingns')
                    ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('creative_logs');
    }

}
