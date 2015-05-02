<?php namespace jorenvanhocht\Blogify\Controllers\admin;

use DB;
use jorenvanhocht\Blogify\Models\Post;

class ApiController extends BaseController {

    /**
     * Holds an instance of
     * the Blogify config file
     *
     * @var object
     */
    protected $config;

    protected $post;

    public function __construct( Post $post )
    {
        parent::__construct();

        $this->config   = objectify( config()->get('blogify') );
        $this->post     = $post;
    }

    /**
     * Order the data of a given table on the given column
     * and the given order
     *
     * @param $table
     * @param $column
     * @param $order
     * @param bool $trashed
     * @return string
     */
    public function sort( $table, $column, $order, $trashed = false )
    {
        $data = DB::table( $table );

        // Check for trashed data
        $data = $trashed ? $data->whereNotNull('deleted_at') : $data->whereNull('deleted_at');

        $data = $data->orderBy($column, $order)->paginate( $this->config->items_per_page );

        return $data;
    }

    /**
     * Check if a given slug already exists
     * and when it exists generate a new one
     *
     * @param $slug
     * @return string
     */
    public function checkIfSlugIsUnique( $slug )
    {
        $posts = $this->post->whereSlug( $slug )->get();

        if ( count($posts) <= 0 ) return $slug;

        $next = count($posts) + 1;
        $slug = $slug . '-' . $next;

        return $this->checkIfSlugIsUnique( $slug );
    }

}