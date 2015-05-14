<?php namespace jorenvanhocht\Blogify\Controllers\Admin;

use Illuminate\Database\DatabaseManager;
use jorenvanhocht\Blogify\Exceptions\BlogifyException;
use jorenvanhocht\Blogify\Models\Post;
use Input;
use Illuminate\Contracts\Cache\Repository;
use Carbon\Carbon;

class ApiController extends BaseController {

    /**
     * Holds an instance of the Post model
     *
     * @var Post
     */
    protected $post;

    /**
     * Holds the base slug
     *
     * @var string
     */
    protected $base_slug;

    /**
     * Construct the class
     *
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        parent::__construct();

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
     * @param DatabaseManager $db
     * @return mixed
     */
    public function sort( $table, $column, $order, $trashed = false, DatabaseManager $db )
    {
        $data = $db->table( $table );

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
        $i                  = 0;
        $this->base_slug    = $slug;

        while( $this->post->whereSlug( $slug )->get()->count() > 0 )
        {
            $i++;
            $slug = $this->base_slug . '-' . $i;
        }

        return $slug;
    }

    /**
     * Save the current post in the cache
     *
     * @param Repository $cache
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function autoSave( Repository $cache )
    {
        try
        {
            $hash = $this->auth_user->hash;
            $cache->put( "autoSavedPost-$hash", Input::all(), Carbon::now()->addHours(2) );
        }
        catch( BlogifyException $exception )
        {
            return response()->json([ false, date('d-m-Y H:i:s')] );
        }

        return response()->json( [true, date('d-m-Y H:i:s')] );
    }

}