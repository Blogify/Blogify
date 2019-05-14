<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPasswordFieldToPostsTable extends Migration {

    public function up()
    {
        Schema::table('blogify_posts', function(Blueprint $table) {
            $table->string('password')->after('being_edited_by')->nullable();
        });
    }

    public function down()
    {
        Schema::table('blogify_posts', function(Blueprint $table)
        {
            $table->dropColumn('password');
        });
    }
}