<?php

namespace jorenvanhocht\Blogify\Commands;

use Illuminate\Console\Command;

class BlogifySeedCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'blogify:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database tables of the Blogify package';

    /**
     * @var string
     */
    protected $class;

    /**
     * Construct the class
     */
    public function __construct()
    {
        parent::__construct();

        $this->class = 'jorenvanhocht\Blogify\database\Seeds\BlogifySeeder';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->call('db:seed', ['--class' => $this->class]);
    }

}
