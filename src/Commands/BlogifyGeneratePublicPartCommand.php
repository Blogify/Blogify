<?php namespace jorenvanhocht\Blogify\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class BlogifyGeneratePublicPartCommand extends Command {

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

    public function __construct( )
    {
        parent::__construct();

        $this->fillTemplatesArray();

        $this->views = [
            'index' => __DIR__."/../../Example/Views/index.blade.php",
            'show' =>__DIR__."/../../Example/Views/show.blade.php",
        ];

        $this->layouts = [
            'master' => __DIR__."/../../Example/Views/templates/master.blade.php",
        ];

        $this->js = [
            'custom' => __DIR__."/../../Example/Public/js/custom.js",
        ];
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

        if ( ! $this->option('only-backend') )
        {
            $this->publishViews();
            $this->info('Views are created');
            $this->publishAssets();
            $this->info('Assets are created');
        }

        $this->info('Public part has successfully been created');
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
     * @return void
     */
    private function fillTemplatesArray()
    {
        $this->templates = [
            'controllers' => [
                'BlogController' => __DIR__.'/../../Example/Controllers/BlogController.txt',
                'CommentsController' => __DIR__.'/../../Example/Controllers/CommentsController.txt',
            ],
            'requests' => [
                'CommentRequest' => __DIR__.'/../../Example/Requests/CommentRequest.txt',
            ]
        ];
    }

    /**
     * @param $namespace
     * @return void
     */
    private function publishTemplates($namespace)
    {
        foreach ($this->templates as $key => $files)
        {
            foreach($files as $k => $file)
            {
                $contents = $this->get_file_contents($file);
                $contents = str_replace('{{namespace}}', $namespace.'\Http\Controllers', $contents);

                $key = ucfirst($key);
                $filename = __DIR__."/../../../../../app/Http/$key/".$k.'.php';

                if (file_exists($filename))
                {
                    if ($this->confirm("File $k allready exists, do you want to override it? Y/N"))
                    {
                        file_put_contents($filename, $contents);
                    }
                }
                else
                {
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
        $path = __DIR__."/../../../../../resources/views/blogify/templates/";
        if (!file_exists($path)) mkdir($path, 775, true);

        foreach ($this->views as $key => $file)
        {
            $filename = __DIR__."/../../../../../resources/views/blogify/$key.blade.php";
            if (file_exists($filename))
            {
                if ($this->confirm("File $key allready exists, do you want to override it? Y/N"))
                {
                    copy($file, $filename);
                }
            }
            else
            {
                copy($file, $filename);
            }
        }

        foreach ($this->layouts as $key => $file)
        {
            $filename = __DIR__."/../../../../../resources/views/blogify/templates/$key.blade.php";
            if (file_exists($filename))
            {
                if ($this->confirm("File $key allready exists, do you want to override it? Y/N"))
                {
                    copy($file, $filename);
                }
            }
            else
            {
                copy($file, $filename);
            }

        }
    }

    /**
     * @return void
     */
    private function publishAssets()
    {
        $path = __DIR__."/../../../../../public/assets/";
        if (!file_exists($path)) mkdir($path, 775, true);

        foreach ($this->js as $key => $file)
        {
            $filename = "$path$key.js";
            if (file_exists($filename))
            {
                if ($this->confirm("File $key allready exists, do you want to override it? Y/N"))
                {
                    copy($file, $filename);
                }
            }
            else
            {
                copy($file, $filename);
            }
        }
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
