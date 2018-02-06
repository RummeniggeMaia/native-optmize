<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostbacksTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('postbacks', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->double('amt')->nullable();
            $table->string('ip')->nullable();
            $table->timestamps();
        });

        Schema::table('postbacks', function(Blueprint $table) {
            $table->bigInteger('click_id')->unsigned()->nullable();
            $table->foreign('click_id')->references('id')->on('clicks')
                    ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('postbacks');
    }

}
