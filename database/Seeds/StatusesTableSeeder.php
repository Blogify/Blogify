<?php

namespace jorenvanhocht\Blogify\database\Seeds;

use Illuminate\Database\Seeder;
use jorenvanhocht\Blogify\Models\Status;

class StatusesTableSeeder extends Seeder
{

    public function run()
    {
        Status::create([
            "hash" => blogify()->makeHash('blogify_statuses', 'hash', true),
            "name" => "Draft",
        ]);

        Status::create([
            "hash" => blogify()->makeHash('blogify_statuses', 'hash', true),
            "name" => "Pending review",
        ]);

        Status::create([
            "hash" => blogify()->makeHash('blogify_statuses', 'hash', true),
            "name" => "Reviewed",
        ]);
    }

}