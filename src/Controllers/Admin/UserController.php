<?php

namespace jorenvanhocht\Blogify\Controllers\Admin;

use jorenvanhocht\Blogify\Blogify;
use jorenvanhocht\Blogify\Models\Role;
use jorenvanhocht\Blogify\Requests\UserRequest;
use App\User;
use Illuminate\Contracts\Hashing\Hasher as Hash;
use jorenvanhocht\Blogify\Services\BlogifyMailer;
use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Tracert\Tracert;

class UserController extends BaseController
{

    /**
     * @var \App\User
     */
    protected $user;

    /**
     * @var \jorenvanhocht\Blogify\Models\Role
     */
    protected $role;

    /**
     * @var \jorenvanhocht\Blogify\Services\BlogifyMailer
     */
    protected $mail;

    /**
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hash;

    /**
     * @var \jorenvanhocht\Blogify\Blogify
     */
    protected $blogify;

    /**
     * @param \App\User $user
     * @param \jorenvanhocht\Blogify\Models\Role $role
     * @param \jorenvanhocht\Blogify\Services\BlogifyMailer $mail
     * @param \Illuminate\Contracts\Hashing\Hasher $hash
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @param \jorenvanhocht\Blogify\Blogify $blogify
     * @param \jorenvanhocht\Tracert\Tracert $tracert
     */
    public function __construct(
        User $user,
        Role $role,
        BlogifyMailer $mail,
        Hash $hash,
        Guard $auth,
        Blogify $blogify,
        Tracert $tracert
    ) {
        parent::__construct($auth);

        $this->user = $user;
        $this->role = $role;
        $this->mail = $mail;
        $this->hash = $hash;
        $this->blogify = $blogify;
        $this->tracert = $tracert;
    }

    ///////////////////////////////////////////////////////////////////////////
    // View methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @param bool $trashed
     * @return \Illuminate\View\View
     */
    public function index($trashed = false)
    {
        $data = [
            'users' => (! $trashed) ?
                    $this->user
                        ->orderBy('lastname', 'ASC')
                        ->paginate($this->config->items_per_page)
                    :
                    $this->user
                        ->onlyTrashed()
                        ->orderBy('name', 'ASC')
                        ->paginate($this->config->items_per_page),
            'trashed' => $trashed,
        ];

        return view('blogify::admin.users.index', $data);
    }

    /**
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
     * @param string $hash
     * @return \Illuminate\View\View
     */
    public function edit($hash)
    {
        $data = [
            'roles' => $this->role->all(),
            'user'  => $this->user->byHash($hash),
        ];

        return view('blogify::admin.users.form', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    // CRUD methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @param \jorenvanhocht\Blogify\Requests\UserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request)
    {
        $data = $this->storeOrUpdateUser($request);
        $user = $data['user'];
        $mail_data = [
            'user'      => $data['user'],
            'password'  => $data['password'],
        ];

        $this->mail->mailPassword($user->email, 'Blogify temperary password', $mail_data);

        //$this->tracert->log('users', $user->id, $this->auth_user->id);

        $message = trans('blogify::notify.success', [
            'model' => 'User', 'name' => $user->fullName, 'action' =>'created'
        ]);
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.users.index');
    }

    /**
     * @param \jorenvanhocht\Blogify\Requests\UserRequest $request
     * @param string $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, $hash)
    {
        $data = $this->storeOrUpdateUser($request, $hash);
        $user = $data['user'];
        $message = trans('blogify::notify.success', [
            'model' => 'User',
            'name' => $user->firstname.' '.$user->name,
            'action' =>'updated'
        ]);

        //$this->tracert->log('users', $user->id, $this->auth_user->id, 'update');

        session()->flash('notify', ['success', $message]);
        return redirect()->route('admin.users.index');
    }

    /**
     * @param string $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($hash)
    {
        $user = $this->user->byHash($hash);
        $user->delete();

        //$this->tracert->log('users', $user->id, $this->auth_user->id, 'delete');

        $message = trans('blogify::notify.success', [
            'model' => 'User', 'name' => $user->fullName, 'action' =>'deleted'
        ]);
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.users.index');
    }

    /**
     * @param string $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($hash)
    {
        $user = $this->user->withTrashed()->byHash($hash);
        $user->restore();

        $message = trans('blogify::notify.success', [
            'model' => 'Post', 'name' => $user->fullName, 'action' =>'restored'
        ]);
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.users.index');
    }

    ///////////////////////////////////////////////////////////////////////////
    // Helper methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @param \jorenvanhocht\Blogify\Requests\UserRequest $data
     * @param string $hash
     * @return array
     */
    private function storeOrUpdateUser($data, $hash = null)
    {
        $password = null;

        if (! isset($hash)) {
            $password = $this->blogify->makeHash();
            $user = new User;
            $user->hash = $this->blogify->makeHash('users', 'hash', true);
            $user->password = $this->hash->make($password);
            $user->username = $this->blogify->generateUniqueUsername($data->name, $data->firstname);
            $user->lastname = $data->name;
            $user->firstname = $data->firstname;
            $user->email = $data->email;
        } else {
            $user = $this->user->byHash($hash);
        }

        $user->role_id = $this->role->byHash($data->role)->id;
        $user->save();

        return ['user' => $user, 'password' => $password];
    }

}