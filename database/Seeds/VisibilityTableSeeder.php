<?php

namespace Donatix\Blogify\database\Seeds;

use Illuminate\Database\Seeder;
use Donatix\Blogify\Models\Visibility;

class VisibilityTableSeeder extends Seeder
{

    public function run()
    {
        Visibility::create([
            "hash" => blogify()->makeHash('visibility', 'hash', true),
            "name" => "Public",
        ]);

        Visibility::create([
            "hash" => blogify()->makeHash('visibility', 'hash', true),
            "name" => "Protected",
        ]);

        Visibility::create([
            "hash" => blogify()->makeHash('visibility', 'hash', true),
            "name" => "Private",
        ]);
    }
}
