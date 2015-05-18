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


    protected $templates;

    protected $files;

    /**
     * Construct the class
     */
    public function __construct( )
    {
        parent::__construct();

        $this->fillTemplatesArray();
        $this->fillFilesArray();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $namespace = $this->option('namespace');

        $this->publishControllers($namespace);
        $this->publishRequests($namespace);

        if ( ! $this->getOptions('only-backend') )
        {
            $this->publishViews();
            $this->publishAssets();
        }
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

    private function fillFilesArray()
    {
        $this->files = [
            'public' => [
                'js' => [
                    __DIR__.'/../../Example/Public/js/custom.js',
                ],
            ],
            'resources' => [
                'views' => [
                    'templates' => [
                        __DIR__.'/../../Example/Views/templates/master.blade.php',
                    ],
                    __DIR__.'/../../Example/Views/index.blade.php',
                    __DIR__.'/../../Example/Views/show.blade.php',
                ],
            ],
        ];
    }

    private function publishControllers($namespace)
    {
        foreach ($this->templates['controllers'] as $key => $file)
        {
            $contents = $this->get_file_contents($file);
            $contents = str_replace('{{namespace}}', $namespace.'\Http\Controllers', $contents);
            $filename = __DIR__.'/../../../../../app/Http/Controllers/'.$key.'.php';

            if (file_exists($filename))
            {
                if ($this->confirm("File $key allready exists, do you want to override it? Y/N"))
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

    private function publishRequests($namespace)
    {
        foreach($this->templates['requests'] as $key => $file)
        {
            $contents = $this->get_file_contents($file);
            $contents = str_replace('{{namespace}}', $namespace.'\Http\Controllers', $contents);
            $filename = __DIR__.'/../../../../../app/Http/Requests/'.$key.'.php';

            if (file_exists($filename))
            {
                if ($this->confirm("File $key allready exists, do you want to override it? Y/N"))
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

    private function publishViews()
    {
        foreach($this->files as $key => $var)
        {
            foreach ( $key as $k => $file)
            {

            }
        }
    }

    private function publishAssets()
    {

    }

    private function get_file_contents($file)
    {
        return file_get_contents($file);
    }
}
