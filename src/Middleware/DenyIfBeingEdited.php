<?php

namespace jorenvanhocht\Blogify\Middleware;

use App\User;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Blogify\Models\Post;

class DenyIfBeingEdited
{

    /**
     * Holds the Guard Contract
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * @var \jorenvanhocht\Blogify\Models\Post
     */
    protected $post;

    /**
     * @var \App\User
     */
    protected $user;

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @param \jorenvanhocht\Blogify\Models\Post $post
     * @param \App\User $user
     */
    public function __construct(Guard $auth, Post $post, User $user)
    {
        $this->auth = $auth;
        $this->post = $post;
        $this->user = $user;
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
        $id = $request->segment(3);
        $post = $this->post->find($id);

        if (
            $post->being_edited_by != null &&
            $post->being_edited_by != $this->auth->user()->getAuthIdentifier()
        ) {
            $user = $this->user->find($post->being_edited_by)->fullName;

            session()->flash('notify', ['danger', trans('blogify::posts.notify.being_edited', ['name' => $user])]);
            return redirect()->route('admin.posts.index');
        }

        return $next($request);
    }

}
