<?php

namespace jorenvanhocht\Blogify\Middleware;

use \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Closure;
use Illuminate\Session\TokenMismatchException;

class BlogifyVerifyCsrfToken extends VerifyCsrfToken
{

    /**
     * @var array
     */
    protected $routes = [
        'admin/posts/image/upload',
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param callable $next
     * @return \Illuminate\Http\Response
     * @throws TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        if (
            $this->isReading($request) ||
            $this->excludedRoutes($request) ||
            $this->tokensMatch($request)
        ) {
            return $this->addCookieToResponse($request, $next($request));
        }

        throw new TokenMismatchException;
    }

    /**
     * @param $request
     * @return bool
     */
    protected function excludedRoutes($request)
    {
        foreach ($this->routes as $route) {
            if ($request->is($route)) {
                return true;
            }
        }

        return false;
    }

}

