<?php namespace jorenvanhocht\Blogify\Controllers\Admin;

use jorenvanhocht\Blogify\Requests\LoginRequest;

class AuthController extends BaseController{

    /**
     * Construct the class
     *
     */
    public function __construct()
    {
        parent::__construct();
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
    public function login( LoginRequest $request )
    {
        if ( $this->auth->attempt( ['email' => $request->email, 'password'  =>  $request->password  ] ) )
        {
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
        $this->auth->logout();
        return redirect()->route('admin.login');
    }
}