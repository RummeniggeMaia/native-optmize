<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaingnCreativeTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('campaingn_creative', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('campaingn_creative', function(Blueprint $table) {
            $table->bigInteger('campaingn_id')->unsigned();
            $table->bigInteger('creative_id')->unsigned();
            $table->foreign('campaingn_id')->references('id')
                    ->on('campaingns')->onDelete('cascade');
            $table->foreign('creative_id')->references('id')
                    ->on('creatives')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('campaingn_creative');
    }

}
