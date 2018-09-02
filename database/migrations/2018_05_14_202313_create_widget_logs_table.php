<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_logs', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->timestamps();
            $table->integer('clicks')->default(0);
            $table->integer('impressions')->default(0);
            $table->double('revenues')->default(0);
        });

        Schema::table('widget_logs', function(Blueprint $table) {
            $table->bigInteger('widget_id')->unsigned()->nullable();
            $table->foreign('widget_id')->references('id')
                    ->on('widgets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget_logs');
    }
}
