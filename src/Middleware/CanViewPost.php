<?php namespace jorenvanhocht\Blogify\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Blogify\Models\Post;

class CanViewPost
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Holds an instance of the post model
     *
     * @var Post
     */
    protected $post;

    /**
     * Create a new filter instance.
     *
     * @param Post $post
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
        if (! $this->checkIfUserCanViewPost($request)) return redirect()->route('/');

        return $next($request);
    }

    /**
     * Check if the user has permission
     * to edit the requested post
     *
     * @return bool
     */
    private function checkIfUserCanViewPost($request)
    {
        $post = $this->post->byHash($request->segment(3));
        $user_id = $this->auth->user()->id;

        if ($post->visibility_id == 'Private')
        {
            if (! $post->user_id == $user_id) return false;
        }

        return true;
    }

}
