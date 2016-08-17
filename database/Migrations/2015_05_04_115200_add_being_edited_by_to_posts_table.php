<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBeingEditedByToPostsTable extends Migration {

    public function up()
    {
        Schema::table('blogify_posts', function(Blueprint $table) {
            $table->integer('being_edited_by')->after('publish_date')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('blogify_posts', function(Blueprint $table)
        {
            $table->dropColumn('being_edited_by');
        });
    }
}