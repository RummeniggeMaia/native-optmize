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
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('url');
            // $table->string('url_mobile');
            $table->string('image');
            $table->integer('type_layout')->default(0);
            $table->double('yield')->default(0.0);
            $table->double('ecpm')->default(0.0);
            $table->integer('ecpmCounter')->default(0);
            $table->double('revenue')->default(0.0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::table('creatives', function(Blueprint $table) {
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')
                    ->on('categories')->onDelete('set null');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                    ->onDelete('cascade');
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
