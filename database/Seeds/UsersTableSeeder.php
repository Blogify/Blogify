<?php

namespace jorenvanhocht\Blogify\database\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use jorenvanhocht\Blogify\Models\Role;
use \Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{

    /**
     * The id of the admin role
     *
     * @var
     */
    private $admin_role;

    /**
     * Admin user information
     *
     * @var mixed
     */
    private $admin;

    public function __construct()
    {
        $this->admin = config('blogify.blogify.admin_user');

        $role = Role::where('name', '=', 'Admin')->first();
        $this->admin_role = $role->id;
    }

    public function run()
    {
        $user = app()->make(config(('blogify.auth_model')));
        $user->create([
            'hash' => blogify()->makeHash(config('blogify.blogify.users_table'), 'hash', true),
            'lastname' => $this->admin['name'],
            'firstname' => $this->admin['firstname'],
            'username' => $this->admin['username'],
            'email' => $this->admin['email'],
            'password' => Hash::make($this->admin['password']),
            'role_id' => $this->admin_role,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

}