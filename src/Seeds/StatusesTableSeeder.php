<?php namespace jorenvanhocht\Blogify\Seeds;

use Illuminate\Database\Seeder;
use jorenvanhocht\Blogify\Facades\Blogify;
use jorenvanhocht\Blogify\Models\Status;

class StatusesTableSeeder extends Seeder {

    public function run()
    {
        Status::create([
            "hash"          => Blogify::makeUniqueHash('statuses', 'hash'),
            "name"          => "Draft",
        ]);

        Status::create([
            "hash"          => Blogify::makeUniqueHash('statuses', 'hash'),
            "name"          => "Pending review",
        ]);

        Status::create([
            "hash"          => Blogify::makeUniqueHash('statuses', 'hash'),
            "name"          => "Reviewed",
        ]);
    }

}