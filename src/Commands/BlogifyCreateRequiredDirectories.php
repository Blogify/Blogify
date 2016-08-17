<?php

namespace jorenvanhocht\Blogify\Commands;

use File;
use Illuminate\Console\Command;

class BlogifyCreateRequiredDirectories extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'blogify:create-dirs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all the required directories for the Blogify package';

    /**
     * @var object
     */
    protected $config;

    public function __construct()
    {
        parent::__construct();

        $this->config = objectify(config('blogify'));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        foreach ($this->config->upload_paths as $paths) {
            foreach ($paths as $path) {
                if (! file_exists(env('PUBLIC_PATH').($path))) {
                    File::makeDirectory(public_path($path), 0775, true);
                }
            }
        }
    }

}
