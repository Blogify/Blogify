<?php namespace jorenvanhocht\Blogify\Commands;

use Illuminate\Console\Command;

class BlogifyMigrateCommand extends Command {

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
     * @var string
     */
    protected $path;

    /**
     * Construct the class
     */
    public function __construct()
    {
        parent::__construct();

        $this->path = 'vendor/jorenvanhocht/blogify/src/Migrations';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->call('migrate', ['--path' => $this->path]);
    }

}
