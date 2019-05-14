<?php

namespace jorenvanhocht\Blogify\Controllers\Admin;

use Illuminate\Database\DatabaseManager;
use Illuminate\Http\Request;
use jorenvanhocht\Blogify\Exceptions\BlogifyException;
use jorenvanhocht\Blogify\Models\Post;
use Illuminate\Contracts\Cache\Repository as Cache;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Blogify\Models\Tag;

class ApiController extends BaseController
{

    /**
     * @var \jorenvanhocht\Blogify\Models\Post
     */
    protected $post;

    /**
     * Holds the base slug
     *
     * @var string
     */
    protected $base_slug;

    /**
     * @param \jorenvanhocht\Blogify\Models\Post $post
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Post $post, Guard $auth)
    {
        parent::__construct($auth);

        $this->post = $post;
    }

    /**
     * Order the data of a given table on the given column
     * and the given order
     *
     * @param string $table
     * @param string $column
     * @param string $order
     * @param bool $trashed
     * @param \Illuminate\Database\DatabaseManager $db
     * @return object
     */
    public function sort(
        $table,
        $column,
        $order,
        $trashed = false,
        DatabaseManager $db
    ) {
        $table = 'blogify_' . $table;
        $db = $db->connection();
        $data = $db->table($table);

        // Check for trashed data
        $data = $trashed ? $data->whereNotNull('deleted_at') : $data->whereNull('deleted_at');

        if ($table == 'blogify_users') {
            $data = $data->join('blogify_roles', 'blogify_users.role_id', '=', 'blogify_roles.id');
        }

        if ($table == 'blogify_posts') {
            $data = $data->join('blogify_statuses', 'blogify_posts.status_id', '=', 'blogify_statuses.id');
        }

        $data = $data
            ->orderBy($column, $order)
            ->paginate($this->config->items_per_page);

        return $data;
    }

    /**
     * Check if a given slug already exists
     * and when it exists generate a new one
     *
     * @param string $slug
     * @return string
     */
    public function checkIfSlugIsUnique($slug)
    {
        $i = 0;
        $this->base_slug = $slug;

        while ($this->post->whereSlug($slug)->get()->count() > 0) {
            $i++;
            $slug = "$this->base_slug-$i";
        }

        return $slug;
    }

    /**
     * Save the current post in the cache
     *
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @param \Illuminate\Http\Request $request;
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function autoSave(Cache $cache, Request $request)
    {
        try {
            $hash = $this->auth_user->hash;
            $cache->put(
                "autoSavedPost-$hash",
                $request->all(),
                Carbon::now()->addHours(2)
            );
        } catch (BlogifyException $exception) {
            return response()->json([false, date('d-m-Y H:i:s')]);
        }

        return response()->json([true, date('d-m-Y H:i:s')]);
    }

    /**
     * @param $hash
     * @param \jorenvanhocht\Blogify\Models\Tag $tag
     * @return mixed
     */
    public function getTag($hash, Tag $tag)
    {
        return $tag->byHash($hash);
    }
    
}