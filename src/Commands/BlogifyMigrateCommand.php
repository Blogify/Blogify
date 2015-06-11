<?php

namespace jorenvanhocht\Blogify\Commands;

use Illuminate\Console\Command;

class BlogifyMigrateCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'blogify:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the migrations for the Blogify package';

    /**
     * @var array
     */
    protected $paths;

    /**
     * Construct the class
     */
    public function __construct()
    {
        parent::__construct();

        $this->paths = [
            'vendor/jorenvanhocht/blogify/database/Migrations',
            'vendor/jorenvanhocht/tracert/database/Migrations',
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        foreach ($this->paths as $path) {
            $this->call('migrate', ['--path' => $path]);
        }
    }

}
