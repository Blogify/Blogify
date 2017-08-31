<?php

namespace Donatix\Blogify\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Donatix\Blogify\Models\Post;

class CanEditPost
{

    /**
     * The Guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * @var \Donatix\Blogify\Models\Post
     */
    protected $post;

    /**
     * Create a new filter instance.
     *
     * @param \Donatix\Blogify\Models\Post $post
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth, Post $post)
    {
        $this->auth = $auth;
        $this->post = $post;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $this->checkIfUserCanEditPost($request)) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    private function checkIfUserCanEditPost($request)
    {
        $post = $this->post->byHash($request->segment(3));
        $user_id = $this->auth->user()->getAuthIdentifier();

        if (
            $user_id != $post->user_id &&
            $user_id != $post->reviewer_id &&
            $this->auth->user()->role->name != 'Admin'
        ) {
            return false;
        }

        return true;
    }

}
