<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublishDateToPostsTable extends Migration {

    public function up()
    {
        Schema::table('blogify_posts', function(Blueprint $table) {
            $table->dateTime('publish_date')->after('status_id');
        });
    }

    public function down()
    {
        Schema::table('blogify_posts', function(Blueprint $table)
        {
            $table->dropColumn('publish_date');
        });
    }
}