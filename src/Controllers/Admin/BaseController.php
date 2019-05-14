<?php

namespace jorenvanhocht\Blogify\Controllers\Admin;

use jorenvanhocht\Blogify\Controllers\Admin;
use Illuminate\Contracts\Auth\Guard;

class BaseController extends Controller
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Holds the logged in user
     *
     * @var bool|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected $auth_user;

    /**
     * @var \Illuminate\Support\Facades\Config
     */
    protected $config;

    /**
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this->config = objectify(config('blogify'));

        $this->middleware(function ($request, $next) {
            $this->auth_user = $this->auth->check() ? $this->auth->user() : false;

            return $next($request);
        });

    }

}
