<?php

namespace jorenvanhocht\Blogify\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Blogify\Models\Post;

class CanViewPost
{

    /**
     * The Guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * @var \jorenvanhocht\Blogify\Models\Post
     */
    protected $post;

    /**
     * Create a new filter instance.
     *
     * @param \jorenvanhocht\Blogify\Models\Post $post
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
        if (! $this->checkIfUserCanViewPost($request)) {
            return redirect()->route('/');
        }

        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    private function checkIfUserCanViewPost($request)
    {
        $post = $this->post->find($request->segment(3));
        $user_id = $this->auth->user()->getAuthIdentifier();

        if ($post->visibility_id == 'Private') {
            if (! $post->user_id == $user_id) {
                return false;
            }
        }

        return true;
    }

}
