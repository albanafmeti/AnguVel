<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFbAppResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_app_results', function (Blueprint $table) {
            $table->increments('id');
            $table->string("app_id");
            $table->string("user_id");
            $table->string("user_name");
            $table->string("image_url")->nullable();
            $table->string("title")->nullable();
            $table->string("description")->nullable();
            $table->text("data")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fb_app_results');
    }
}
