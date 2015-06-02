<?php namespace jorenvanhocht\Blogify\Middleware;

use Closure;
use Illuminate\Support\Facades\Input;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Hashing\Hasher;
use jorenvanhocht\Blogify\Models\Post;

class ProtectedPost
{

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

    protected $hash;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     * @param Post $post
     */
    public function __construct(Guard $auth, Post $post, Hasher $hash)
    {
        $this->auth = $auth;
        $this->post = $post;
        $this->hash = $hash;
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

        if ($post->visibility_id == 2) {

            if (!$this->hash->check(Input::get('password'), $post->password))
            {
                return redirect()->route('blog.askPassword', [$post->slug])
                    ->with(
                        'wrong_password',
                        'Please provide a valid password to view this post'
                    );
            }

        }

        return $next($request);
    }

}
