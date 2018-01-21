<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreativesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('creatives', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('hashid')->nullable();
            $table->char('name', 255);
            $table->char('url', 255);
            $table->char('image', 255);
            $table->timestamps();
        });

        Schema::table('creatives', function(Blueprint $table) {
            $table->bigInteger('related_category')->unsigned();
            $table->foreign('related_category')->references('id')->on('categories');

            $table->bigInteger('owner')->unsigned();
            $table->foreign('owner')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('creatives');
    }

}
