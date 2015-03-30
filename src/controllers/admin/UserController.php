<?php
namespace jorenvanhocht\Blogify\Controllers\Admin;

use App\Http\Controllers\Controller;
use jorenvanhocht\Blogify\Models\Role;
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

    /**
     * Holds an instance of the Role model
     *
     * @var
     */
    private $role;

    /**
     * Post data
     *
     * @var
     */
    private $data;

    public function __construct( User $user, Role $role )
    {
        $this->user = $user;
        $this->role = $role;
    }

    ///////////////////////////////////////////////////////////////////////////
    // View methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Show the view with all the active users
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data = [
            'users'     => User::all(),
            'trashed'   => false,
        ];

        return View('blogify::admin.users.index', $data);
    }

    /**
     * Show the view with all deleted users
     *
     * @return \Illuminate\View\View
     */
    public function trashed()
    {
        $data = [
            'users'     => User::onlyTrashed()->get(),
            'trashed'   => true,
        ];

        return View('blogify::admin.users.index', $data);
    }

    /**
     * Show the view to create a new user
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $data = [
            'roles' => $this->role->all(),
        ];

        return View('blogify::admin.users.form', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    // CRUD methods
    ///////////////////////////////////////////////////////////////////////////

    public function store()
    {

    }

}