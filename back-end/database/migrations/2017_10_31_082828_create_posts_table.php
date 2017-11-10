<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->index()->unique();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('small_content');
            $table->text('content');
            $table->string('image')->nullable();
            $table->string('author')->default("Admin");
            $table->enum('enabled', [0, 1])->default(1);
            $table->enum('featured', [0, 1])->default(0);
            $table->timestamps();
        });

        Schema::create('category_post', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("category_id")->unsigned();
            $table->integer("post_id")->unsigned();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });

        Schema::create('post_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->unsigned();

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');

            $table->string('path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_images');
        Schema::dropIfExists('category_post');
        Schema::dropIfExists('posts');
    }
}
