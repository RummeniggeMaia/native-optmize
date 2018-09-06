<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->string('original_name');
            $table->string('path');
            $table->integer('impressions')->default(0);
            $table->timestamps();
        });
        Schema::table('images', function(Blueprint $table) {
            $table->bigInteger('creative_id')->unsigned();
            $table->foreign('creative_id')->references('id')->on('creatives')
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
        Schema::dropIfExists('images');
    }
}
