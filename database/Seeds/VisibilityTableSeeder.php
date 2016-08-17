<?php

namespace jorenvanhocht\Blogify\database\Seeds;

use Illuminate\Database\Seeder;
use jorenvanhocht\Blogify\Models\Visibility;

class VisibilityTableSeeder extends Seeder
{

    public function run()
    {
        Visibility::create([
            "hash" => blogify()->makeHash('blogify_visibility', 'hash', true),
            "name" => "Public",
        ]);

        Visibility::create([
            "hash" => blogify()->makeHash('blogify_visibility', 'hash', true),
            "name" => "Protected",
        ]);

        Visibility::create([
            "hash" => blogify()->makeHash('blogify_visibility', 'hash', true),
            "name" => "Private",
        ]);
    }

}