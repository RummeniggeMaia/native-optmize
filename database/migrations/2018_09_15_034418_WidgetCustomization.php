<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WidgetCustomization extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_customizations', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('image_width')->default('240')->nullable();
            $table->string('image_height')->default('180')->nullable();
            $table->string('title_color')->default('white')->nullable();
            $table->string('title_hover_color')->default('blue')->nullable();
            $table->string('text_color')->default('white')->nullable();
            $table->string('card_body_color')->default('black')->nullable();
            $table->timestamps();
        });

        Schema::table('widget_customizations', function(Blueprint $table) {
            $table->bigInteger('widget_id')->unsigned();
            $table->foreign('widget_id')->references('id')->on('widgets')
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
        Schema::dropIfExists('widgetcustomizations');
    }
}
