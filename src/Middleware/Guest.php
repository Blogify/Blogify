<?php

namespace jorenvanhocht\Blogify\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Guest
{

    /**
     * The Guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;


    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
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
        if ( ! $this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            }

            return redirect('admin');
        }

        return $next($request);
    }

}
