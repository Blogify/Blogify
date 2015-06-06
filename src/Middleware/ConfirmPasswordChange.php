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
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hash;

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
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
