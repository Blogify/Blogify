<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogify_media', function($table)
        {
            $table->increments('id');
            $table->string('hash', 80)->unique();
            $table->string('type', 25);
            $table->string('path', 200);
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
        Schema::dropIfExists('blogify_media');
    }

}
