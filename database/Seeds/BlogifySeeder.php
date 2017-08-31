<?php

namespace Donatix\Blogify\database\Seeds;

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

        $this->call('Donatix\Blogify\database\Seeds\RolesTableSeeder');
        $this->call('Donatix\Blogify\database\Seeds\UsersTableSeeder');
        $this->call('Donatix\Blogify\database\Seeds\StatusesTableSeeder');
        $this->call('Donatix\Blogify\database\Seeds\VisibilityTableSeeder');
    }
}
