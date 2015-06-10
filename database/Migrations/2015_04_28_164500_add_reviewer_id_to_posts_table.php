<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReviewerIdToPostsTable extends Migration {

    public function up()
    {
        Schema::table('posts', function(Blueprint $table) {
            $table->integer('reviewer_id')->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('posts', function(Blueprint $table)
        {
            $table->dropColumn('reviewer_id');
        });
    }
}