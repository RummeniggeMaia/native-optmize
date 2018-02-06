<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClicksTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('clicks', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('click_id')->nullable();
            $table->timestamps();
        });

        Schema::table('clicks', function(Blueprint $table) {
            $table->bigInteger('creative_id')->unsigned();
            $table->foreign('creative_id')->references('id')->on('creatives')
                    ->onDelete('cascade');
            $table->bigInteger('widget_id')->unsigned()->nullable();
            $table->foreign('widget_id')->references('id')->on('widgets')
                    ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('clicks');
    }

}
