<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('widgets', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('hashid')->nullable();
            $table->char('name', 255);
            $table->char('url', 255);
            $table->char('type', 255);
            $table->timestamps();
        });

        Schema::table('widgets', function(Blueprint $table) {
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
        Schema::dropIfExists('widgets');
    }

}
