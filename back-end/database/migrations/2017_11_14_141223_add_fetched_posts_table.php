<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFetchedPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fetched_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fid')->index()->unique();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('small_content')->nullable();
            $table->text('content');
            $table->string('image_url')->nullable();
            $table->string('category')->nullable();
            $table->string('category_mapped')->nullable();
            $table->string('published_at')->nullable();
            $table->string('blog_url');
            $table->enum('imported', [0, 1])->default(0);
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
        Schema::dropIfExists('fetched_posts');
    }
}
