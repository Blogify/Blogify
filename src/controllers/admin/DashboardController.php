<?php namespace jorenvanhocht\Blogify\Controllers\Admin;

use App\User;

class DashboardController extends BaseController {

    /**
     * Holds an instance of the user model
     *
     * @var User
     */
    protected $user;

    /**
     * Holds the data for the dashboard
     *
     * @var array
     */
    protected $data = [];

    /**
     * Construct the class
     *
     * @param User $user
     */
    public function __construct( User $user )
    {
        parent::__construct();

        $this->user = $user;

        $this->buildDataObject();
    }

    ///////////////////////////////////////////////////////////////////////////
    // View methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Show the dashboard view
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view("blogify::admin.home", $this->data);
    }

    ///////////////////////////////////////////////////////////////////////////
    // Helper methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Fill in the array with the data for the dashboard
     *
     */
    public function buildDataObject()
    {
       /* $this->data['new_users_since_last_visit'] = $this->user->newUsersSince( $this->auth_user->updated_at )->count();

        objectify($this->data);*/
    }

}