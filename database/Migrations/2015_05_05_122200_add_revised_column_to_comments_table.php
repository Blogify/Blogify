<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRevisedColumnToCommentsTable extends Migration {

    public function up()
    {
        Schema::table('blogify_comments', function(Blueprint $table) {
            $table->integer('revised')->after('post_id');
        });
    }

    public function down()
    {
        Schema::table('blogify_comments', function(Blueprint $table)
        {
            $table->dropColumn('revised');
        });
    }
}