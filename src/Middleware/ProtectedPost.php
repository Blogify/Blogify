<?php namespace jorenvanhocht\Blogify\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Blogify\Models\Post;

class ProtectedPost {

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * @var Post
     */
    protected $post;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
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
        $post = $this->post->bySlug($request->segment(2));

        if ($post->visibility_id == 2)
        {
            if (!session()->has('protected_post') || session()->get('protected_post') != $post->hash) return redirect()->route('blog.askPassword', [$post->hash]);
        }

        return $next($request);
    }

}
