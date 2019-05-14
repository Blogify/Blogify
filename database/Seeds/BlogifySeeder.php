<?php

namespace jorenvanhocht\Blogify\database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class BlogifySeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('jorenvanhocht\Blogify\database\Seeds\RolesTableSeeder');
        //$this->call('jorenvanhocht\Blogify\database\Seeds\UsersTableSeeder');
        $this->call('jorenvanhocht\Blogify\database\Seeds\StatusesTableSeeder');
        $this->call('jorenvanhocht\Blogify\database\Seeds\VisibilityTableSeeder');
    }

}
