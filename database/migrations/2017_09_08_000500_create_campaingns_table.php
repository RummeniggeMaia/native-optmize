<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaingnsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('campaingns', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('hashid')->nullable();
            $table->string('name');
            $table->string('brand');
            $table->string('type')->default('CPC');
            $table->integer('type_layout')->default(0);
            $table->double('ceiling')->default(0.0);
            $table->double('cpc')->default(0.0);
            $table->double('cpm')->default(0.0);
            $table->date('expires_in');
            $table->timestamps();
        });

        Schema::table('campaingns', function(Blueprint $table) {
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
        Schema::dropIfExists('campaingns');
    }

}
