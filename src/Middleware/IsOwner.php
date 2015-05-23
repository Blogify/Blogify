<?php namespace jorenvanhocht\Blogify\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\User;

class IsOwner
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Holds an instance of the User model
     *
     * @var User
     */
    protected $user;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     * @param User $user
     */
    public function __construct(Guard $auth, User $user)
    {
        $this->auth = $auth;
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
        $user = $this->user->byHash($request->segment(3));

        if ($this->auth->user()->id != $user->id) abort(404);

        return $next($request);
    }

}
