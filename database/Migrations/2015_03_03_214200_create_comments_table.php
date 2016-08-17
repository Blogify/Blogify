<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogify_comments', function($table)
        {
            $table->increments('id');
            $table->string('hash', 80)->unique();
            $table->text('content');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on(config('blogify.blogify.users_table'));
            $table->integer('post_id')->unsigned();
            $table->foreign('post_id')->references('id')->on('blogify_posts');
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
        Schema::dropIfExists('blogify_comments');
    }

}
