<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call('jorenvanhocht\Blogify\Seeds\RolesTableSeeder');
		$this->call('jorenvanhocht\Blogify\Seeds\UsersTableSeeder');
		$this->call('jorenvanhocht\Blogify\Seeds\StatusesTableSeeder');
		$this->call('jorenvanhocht\Blogify\Seeds\VisibilityTableSeeder');
	}

}
