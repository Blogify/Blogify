<?php namespace jorenvanhocht\Blogify\Seeds;

use Illuminate\Database\Seeder;
use jorenvanhocht\Blogify\Facades\Blogify;
use jorenvanhocht\Blogify\Models\Role;
use jorenvanhocht\Blogify\Models\User;

class RolesTableSeeder extends Seeder {

    public function run()
    {
        Role::create([
            "hash"          => Blogify::makeUniqueHash('roles', 'hash'),
            "name"          => "Admin",
        ]);

        Role::create([
            "hash"          => Blogify::makeUniqueHash('roles', 'hash'),
            "name"          => "Author",
        ]);

        Role::create([
            "hash"          => Blogify::makeUniqueHash('roles', 'hash'),
            "name"          => "Editor",
        ]);

        Role::create([
            "hash"          => Blogify::makeUniqueHash('roles', 'hash'),
            "name"          => "Reviewer",
        ]);
    }

}