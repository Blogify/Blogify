<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogify_posts', function($table)
        {
            $table->increments('id');
            $table->string('hash', 80)->unique();
            $table->string('title', 100);
            $table->string('slug', 120)->unique();
            $table->text('short_description');
            $table->longtext('content');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on(config('blogify.blogify.users_table'));
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('blogify_categories');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogify_posts');
    }

}
