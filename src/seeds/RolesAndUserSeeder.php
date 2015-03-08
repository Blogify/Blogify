<?php namespace jorenvanhocht\Blogify\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use jorenvanhocht\Blogify\Facades\Blogify;
use jorenvanhocht\Blogify\Models\Role;
use jorenvanhocht\Blogify\Models\User;
use \Illuminate\Support\Facades\Hash;

class RolesAndUserSeeder extends Seeder {

    private $admin_role;
    private $admin;

    public function __construct()
    {
        $this->admin = config('blogify.blogify.admin_user');
    }

    public function run()
    {
        ///////////////////////////////////////////////////////////////////////////
        // Seed the role table
        ///////////////////////////////////////////////////////////////////////////

        $this->admin_role = Role::create([
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

        ///////////////////////////////////////////////////////////////////////////
        // Seed the users table
        ///////////////////////////////////////////////////////////////////////////

        User::create([
            'hash'          => Blogify::makeUniquehash('users', 'hash'),
            'name'          => $this->admin['name'],
            'firstname'     => $this->admin['firstname'],
            'username'      => $this->admin['username'],
            'email'         => $this->admin['email'],
            'password'      => Hash::make( $this->admin['password'] ),
            'role_id'       => $this->admin_role->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
    }

}