<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaingnWidgetTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('campaingn_widget', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('campaingn_widget', function(Blueprint $table) {
            $table->bigInteger('widget_id')->unsigned();
			$table->foreign('widget_id')->references('id')
                    ->on('widgets')->onDelete('cascade');

            $table->bigInteger('campaingn_id')->unsigned();
            $table->foreign('campaingn_id')->references('id')->
                    on('campaingns')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('campaingn_widget');
    }

}
