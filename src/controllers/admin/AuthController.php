<?php 
namespace jorenvanhocht\Blogify\Controllers\Admin;

use jorenvanhocht\Blogify\Requests\LoginRequest;

class AuthController extends BlogifyController{

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
        return route('admin.login');
    }

    /**
     * Log the user out
     *
     * @return mixed
     */
    public function logout()
    {
        $this->auth->logout();
        return route('admin.login');
    }
}