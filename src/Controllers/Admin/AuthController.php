<?php

namespace jorenvanhocht\Blogify\Controllers\Admin;

use jorenvanhocht\Blogify\Requests\LoginRequest;
use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Tracert\Tracert;

class AuthController extends BaseController
{

    /**
     * @var \jorenvanhocht\Tracert\Tracert
     */
    protected $tracert;

    /**
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @param \jorenvanhocht\Tracert\Tracert $tracert
     */
    public function __construct(Guard $auth, Tracert $tracert)
    {
        parent::__construct($auth);
        $this->tracert = $tracert;
    }

    ///////////////////////////////////////////////////////////////////////////
    // View methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Show the login view
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('blogify::admin.auth.login');
    }

    ///////////////////////////////////////////////////////////////////////////
    // Login methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @param \jorenvanhocht\Blogify\Requests\LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $this->auth->attempt([
            'email' => $request->email,
            'password' => $request->password
        ], isset($request->rememberme) ? true : false);

        if ($credentials) {
            $this->tracert->log(
                'users',
                $this->auth->user()->getAuthIdentifier(),
                $this->auth->user()->getAuthIdentifier(),
                'Login'
            );

            return redirect('/admin');
        }

        session()->flash('message', 'Wrong credentials');

        return redirect()->route('admin.login');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        $user_id = $this->auth_user->id;
        $this->auth->logout();

        $this->tracert->log('users', $user_id, $user_id, 'Logout');

        return redirect()->route('admin.login');
    }
}