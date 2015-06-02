<?php namespace jorenvanhocht\Blogify\Middleware;

use Closure;
use Illuminate\Support\Facades\Input;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Hashing\Hasher;

class ConfirmPasswordChange
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    protected $hash;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth, Hasher $hash)
    {
        $this->auth = $auth;
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
        $newpass = Input::get('newpassword');

        if (! empty($newpass)) {

        }

        return $next($request);
    }

}
