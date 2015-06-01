<?php namespace jorenvanhocht\Blogify\Controllers\Admin;

use jorenvanhocht\Blogify\Requests\LoginRequest;
use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Tracert\Tracert;

class AuthController extends BaseController
{

    /**
     * @var Tracert
     */
    protected $tracert;

    /**
     * @param Guard $auth
     * @param Tracert $tracert
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
     * Log the user in to the application
     *
     * @param LoginRequest $request
     * @return mixed
     */
    public function login(LoginRequest $request)
    {
        $credentials = $this->auth->attempt([
            'email' => $request->email,
            'password' => $request->password
        ], isset($request->rememberme) ? true : false );

        if ($credentials) {
            $this->tracert->log(
                'users',
                $this->auth->user()->id,
                $this->auth->user()->id,
                'Login'
            );

            return redirect('/admin');
        }

        session()->flash('message', 'Wrong credentials');

        return redirect()->route('admin.login');
    }

    /**
     * Log the user out
     *
     * @return mixed
     */
    public function logout()
    {
        $user_id = $this->auth_user->id;
        $this->auth->logout();

        $this->tracert->log('users', $user_id, $user_id, 'Logout');

        return redirect()->route('admin.login');
    }
}