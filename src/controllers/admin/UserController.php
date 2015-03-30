<?php
namespace jorenvanhocht\Blogify\Controllers\Admin;

use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\User;

class UserController extends Controller{

    /**
     * Holds an instance of the User model
     *
     * @var
     */
    private $user;

    public function __construct( User $user )
    {
        $this->user = $user;
    }

    ///////////////////////////////////////////////////////////////////////////
    // View methods
    ///////////////////////////////////////////////////////////////////////////

    public function index()
    {
        $data = [
            'users'     => User::all(),
            'trashed'   => false,
        ];

        return View('blogify::admin.users.index', $data);
    }

    public function trashed()
    {
        $data = [
            'users'     => User::onlyTrashed()->get(),
            'trashed'   => true,
        ];

        return View('blogify::admin.users.index', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    // CRUD methods
    ///////////////////////////////////////////////////////////////////////////


}