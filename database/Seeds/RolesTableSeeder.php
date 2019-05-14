<?php

namespace jorenvanhocht\Blogify\database\Seeds;

use Illuminate\Database\Seeder;
use jorenvanhocht\Blogify\Models\Role;

class RolesTableSeeder extends Seeder {

    public function run()
    {
        Role::create([
            "hash" => blogify()->makeHash('blogify_roles', 'hash', true),
            "name" => "Admin",
        ]);

        Role::create([
            "hash" => blogify()->makeHash('blogify_roles', 'hash', true),
            "name" => "Author",
        ]);

        Role::create([
            "hash" => blogify()->makeHash('blogify_roles', 'hash', true),
            "name" => "Reviewer",
        ]);

        Role::create([
            "hash" => blogify()->makeHash('blogify_roles', 'hash', true),
            "name" => "Member",
        ]);
    }

}