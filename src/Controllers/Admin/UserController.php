<?php namespace jorenvanhocht\Blogify\Controllers\Admin;

use jorenvanhocht\Blogify\Models\Role;
use jorenvanhocht\Blogify\Requests\UserRequest;
use App\User;
use Illuminate\Contracts\Hashing\Hasher as Hash;
use jorenvanhocht\Blogify\Services\BlogifyMailer;

class UserController extends BaseController
{

    /**
     * Holds an instance of the User model
     *
     * @var User
     */
    private $user;

    /**
     * Holds an instance of the Role model
     *
     * @var Role
     */
    private $role;

    /**
     * Holds an instance of the BlogifyMailer class
     *
     * @var BlogifyMailer
     */
    private $mail;

    /**
     * Holds an instance of the Hasher contract
     *
     * @var Hash
     */
    private $hash;

    /**
     * @param User $user
     * @param Role $role
     * @param BlogifyMailer $mail
     * @param Hash $hash
     */
    public function __construct(User $user, Role $role, BlogifyMailer $mail, Hash $hash)
    {
        parent::__construct();

        $this->user = $user;
        $this->role = $role;
        $this->mail = $mail;
        $this->hash = $hash;
    }

    ///////////////////////////////////////////////////////////////////////////
    // View methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Show the view with all the active users
     *
     * @return \Illuminate\View\View
     */
    public function index($trashed = false)
    {
        $data = [
            'users'     => (! $trashed) ? $this->user->orderBy('name', 'ASC')->paginate($this->config->items_per_page) : $this->user->onlyTrashed()->orderBy('name', 'ASC')->paginate($this->config->items_per_page),
            'trashed'   => $trashed,
        ];

        return view('blogify::admin.users.index', $data);
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

        return view('blogify::admin.users.form', $data);
    }

    /**
     * Show the view to edit a given user
     *
     * @param $hash
     * @return \Illuminate\View\View
     */
    public function edit($hash)
    {
        $data = [
            'roles' => $this->role->all(),
            'user'  => $this->user->byHash( $hash ),
        ];

        return view('blogify::admin.users.form', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    // CRUD methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Store a new user in the database
     *
     * @param UserRequest $request
     * @return mixed
     */
    public function store(UserRequest $request)
    {
        $data = $this->storeOrUpdateUser( $request );
        $user = $data['user'];
        $mail_data = [
            'user'      => $data['user'],
            'password'  => $data['password'],
        ];

        $this->mail->mailPassword($user->email, 'Blogify temperary password', $mail_data);

        tracert()->log('users', $user->id, $this->auth_user->id);

        $message = trans('blogify::notify.success', ['model' => 'User', 'name' => generateFullName($user->firstname, $user->name), 'action' =>'created']);
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.users.index');
    }

    /**
     * Update a given user in the database
     *
     * @param UserRequest $request
     * @param $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, $hash)
    {
        $data = $this->storeOrUpdateUser( $request, $hash );
        $user = $data['user'];
        $message = trans('blogify::notify.success', ['model' => 'User', 'name' => generateFullName($user->firstname, $user->name), 'action' =>'updated']);

        tracert()->log('users', $user->id, $this->auth_user->id, 'update');

        session()->flash('notify', ['success', $message]);
        return redirect()->route('admin.users.index');
    }

    /**
     * Delete a given user
     *
     * @param $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($hash)
    {
        $user = $this->user->byHash($hash);
        $name = $user->firstname . ' ' . $user->name;

        $user->delete();

        tracert()->log('users', $user->id, $this->auth_user->id, 'delete');

        $message = trans('blogify::notify.success', ['model' => 'User', 'name' => $name, 'action' =>'deleted']);
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.users.index');
    }

    /**
     * @param $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($hash)
    {
        $user = $this->user->withTrashed()->byHash($hash);
        $fullname = $user->fullName;
        $user->restore();

        $message = trans('blogify::notify.success', ['model' => 'Post', 'name' => $fullname, 'action' =>'restored']);
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.users.index');
    }

    ///////////////////////////////////////////////////////////////////////////
    // Helper methods
    ///////////////////////////////////////////////////////////////////////////

    private function storeOrUpdateUser($data, $hash = null)
    {
        $password = null;

        if (! isset($hash))
        {
            $password           = blogify()->generatePassword();
            $user               = new User;
            $user->hash         = blogify()->makeUniqueHash('users', 'hash');
            $user->password     = $this->hash->make( $password );
            $user->username     = blogify()->generateUniqueUsername( $data->name, $data->firstname );
            $user->name         = $data->name;
            $user->firstname    = $data->firstname;
            $user->email        = $data->email;
        }
        else
        {
            $user = $this->user->byHash($hash);
        }

        $user->role_id = $this->role->byHash($data->role)->id;
        $user->save();

        return ['user' => $user, 'password' => $password];
    }
}