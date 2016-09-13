<?php

namespace jorenvanhocht\Blogify\Commands;

use File;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class BlogifyGeneratePublicPartCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'blogify:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the public part of your blog';

    /**
     * @var array
     */
    protected $templates;

    /**
     * @var array
     */
    protected $views;

    /**
     * @var array
     */
    protected $layouts;

    /**
     * @var string
     */
    protected $routes;

    public function __construct()
    {
        parent::__construct();

        $this->fillTemplatesArray();
        $this->fillViewsArray();
        $this->fillLayoutsArray();
        $this->fillJsArray();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $namespace = $this->option('namespace');

        $this->publishTemplates($namespace);
        $this->info('Controllers and requests created');

        if (! $this->option('only-backend')) {
            $this->publishViews();
            $this->info('Views created');
            $this->publishAssets();
            $this->info('Assets created');
        }

        $this->info('Public part has successfully been created');
        $this->info('Make sure to enable the routes in the config file, by setting "enable_default_routes" to true');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['only-backend', null, InputOption::VALUE_NONE, 'Only generate the backend part (Controllers, Requests, ...)', null],
            ['namespace', null, InputOption::VALUE_REQUIRED, 'Define the namespace of your app', 'App']
        ];
    }

    /**
     * @param $namespace
     * @return void
     */
    private function publishTemplates($namespace)
    {
        foreach ($this->templates as $key => $files) {
            foreach ($files as $k => $file) {
                $contents = $this->get_file_contents($file);
                $contents = str_replace('{{namespace}}', $namespace."\\Http\\$key", $contents);
                $contents = str_replace('{{appnamespace}}', $namespace, $contents);

                $key = ucfirst($key);
                $filename = app_path("Http/$key/$k.php");

                if (File::exists($filename)) {
                    if ($this->confirm("File $k allready exists, do you want to override it? Y/N")) {
                        file_put_contents($filename, $contents);
                    }
                } else {
                    file_put_contents($filename, $contents);
                }
            }
        }
    }

    /**
     * @return void
     */
    private function publishViews()
    {
        $basepath = app_path('../resources/views/blogify/');
        $path = $basepath."templates/";
dd($path);
        if (!File::exists($path)) {
            File::makeDirectory($path, 0775, true);
        }

        foreach ($this->views as $key => $file) {
            $filename = $basepath . "$key.blade.php";
            if (File::exists($filename)) {
                if ($this->confirm("File $key already exists, do you want to override it? Y/N")) {
                    copy($file, $filename);
                }
            } else {
                copy($file, $filename);
            }
        }

        foreach ($this->layouts as $key => $file) {
            $filename = $basepath."templates/$key.blade.php";
            if (File::exists($filename)) {
                if ($this->confirm("File $key already exists, do you want to override it? Y/N")) {
                    copy($file, $filename);
                }
            } else {
                copy($file, $filename);
            }
        }
    }

    /**
     * @return void
     */
    private function publishAssets()
    {
        $path = public_path('/assets/');
        if (! File::exists($path)) {
            File::makeDirectory($path, 0775, true);
        }

        foreach ($this->js as $key => $file) {
            $filename = "$path$key.js";
            if (File::exists($filename)) {
                if ($this->confirm("File $key already exists, do you want to override it? Y/N")) {
                    copy($file, $filename);
                }
            } else {
                copy($file, $filename);
            }
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    // Helper methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @return void
     */
    private function fillTemplatesArray()
    {
        $this->templates = [
            'Controllers' => [
                'BlogController' => __DIR__.'/../../Example/Controllers/BlogController.txt',
                'CommentsController' => __DIR__.'/../../Example/Controllers/CommentsController.txt',
            ],
            'Requests' => [
                'CommentRequest' => __DIR__.'/../../Example/Requests/CommentRequest.txt',
            ]
        ];
    }

    /**
     * @return void
     */
    private function fillViewsArray()
    {
        $this->views = [
            'index' => __DIR__."/../../Example/Views/index.blade.php",
            'show' => __DIR__."/../../Example/Views/show.blade.php",
            'password' => __DIR__."/../../Example/Views/password.blade.php",
        ];
    }

    /**
     * @return void
     */
    private function fillLayoutsArray()
    {
        $this->layouts = [
            'master' => __DIR__."/../../Example/Views/templates/master.blade.php",
        ];
    }

    /**
     * @return void
     */
    private function fillJsArray()
    {
        $this->js = [
            'custom' => __DIR__."/../../Example/Public/js/custom.js",
        ];
    }

    /**
     * @param $file
     * @return string
     */
    private function get_file_contents($file)
    {
        return file_get_contents($file);
    }
}
