<?php

namespace jorenvanhocht\Blogify\Controllers\Admin;

use App\User;
use Illuminate\Contracts\Hashing\Hasher;
use jorenvanhocht\Blogify\Requests\ProfileUpdateRequest;
use Intervention\Image\Facades\Image;
use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Tracert\Tracert;

class ProfileController extends BaseController
{

    /**
     * @var \App\User
     */
    protected $user;

    /**
     * @var \jorenvanhocht\Tracert\Tracert
     */
    protected $tracert;

    /**
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hash;

    /**
     * @param \App\User $user
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @param \jorenvanhocht\Tracert\Tracert $tracert
     * @param \Illuminate\Contracts\Hashing\Hasher $hash
     */
    public function __construct(
        User $user,
        Guard $auth,
        Tracert $tracert,
        Hasher $hash
    ) {
        parent::__construct($auth);

        $this->middleware('IsOwner', ['only', 'edit']);

        $this->user = $user;
        $this->tracert = $tracert;
        $this->hash = $hash;
    }

    ///////////////////////////////////////////////////////////////////////////
    // View methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @param string $hash
     * @return \Illuminate\View\View
     */
    public function edit($hash)
    {
        $data = [
            'user' => $this->user->byHash($hash),
        ];

        return view('blogify::admin.profiles.form', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    // CRUD methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @param string $hash
     * @param \jorenvanhocht\Blogify\Requests\ProfileUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($hash, ProfileUpdateRequest $request)
    {
        $user = $this->user->byHash($hash);
        $user->lastname = $request->name;
        $user->firstname = $request->firstname;
        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->has('newpassword')) {
            $user->password = $this->hash->make($request->newpassword);
        }

        if ($request->hasFile('profilepicture')) {
            $this->handleImage($request->file('profilepicture'), $user);
        }

        $user->save();

        //$this->tracert->log('users', $user->id, $this->auth_user->id, 'update');

        $message = trans('blogify::notify.success', [
            'model' => 'User', 'name' => $user->fullName, 'action' =>'updated'
        ]);
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.dashboard');
    }

    ///////////////////////////////////////////////////////////////////////////
    // Helper methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @param $image
     * @param $user
     */
    private function handleImage($image, $user)
    {
        $filename = $this->generateFilename();
        $path = $this->resizeAndSaveProfilePicture($image, $filename);

        if (isset($user->profilepicture)) {
            $this->removeOldPicture($user->profilepicture);
        }

        $user->profilepicture = $path;
    }

    /**
     * @return string
     */
    private function generateFilename()
    {
        return time().'-'.$this->auth_user->username.'-profilepicture';
    }

    /**
     * @param $image
     * @param string $filename
     * @return string
     */
    private function resizeAndSaveProfilePicture($image, $filename)
    {
        $extention = $image->getClientOriginalExtension();
        $fullpath = env('PUBLIC_PATH') . $this->config->upload_paths->profiles->profilepictures.$filename.'.'.$extention;

        Image::make($image->getRealPath())
            ->resize($this->config->image_sizes->profilepictures[0], $this->config->image_sizes->profilepictures[1], function($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($fullpath);

        return $fullpath;
    }

    /**
     * @param $oldPicture
     */
    private function removeOldPicture($oldPicture)
    {
        if (file_exists($oldPicture)) {
            unlink($oldPicture);
        }
    }

}