<?php 
namespace jorenvanhocht\Blogify\Controllers\Admin;

use App\Http\Controllers\Controller;
use jorenvanhocht\Blogify\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller{

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
        if ( Auth::attempt( ['email' => $request->email, 'password'  =>  $request->password  ] ) )
        {
          return Redirect::to('/admin');
        }

        Session::flash('message', 'Wrong credentials');
        return Redirect::route('admin.login');
    }

    /**
     * Log the user out
     *
     * @return mixed
     */
    public function logout()
    {
        Auth::logout();
        return Redirect::route('admin.login');
    }
}