<?php namespace jorenvanhocht\Blogify\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;

class BaseController extends Controller
{
    /**
     * Holds an instance of the Auth object
     *
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
     * Holds the configuration settings
     *
     * @var
     */
    protected $config;

    /**
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this->config = objectify(config('blogify'));
        $this->auth_user = $this->auth->check() ? $this->auth->user() : false;
    }

}
