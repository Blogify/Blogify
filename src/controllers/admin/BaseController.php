<?php namespace jorenvanhocht\Blogify\Controllers\admin;

use App\Http\Controllers\Controller;

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

    public function __construct()
    {
        $this->auth         = auth();
        $this->auth_user    = $this->auth->check() ? $this->auth->user() : false;
    }
}
