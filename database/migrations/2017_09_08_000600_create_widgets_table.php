<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateWidgetsTable extends Migration {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

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
            $table->softDeletes();
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
        Schema::dropIfExists('widgets');
    }

}
