<?php namespace jorenvanhocht\Blogify\Controllers\Admin;

use App\User;
use jorenvanhocht\Blogify\Requests\ProfileUpdateRequest;
use Intervention\Image\Facades\Image;
use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Tracert\Tracert;

class ProfileController extends BaseController
{

    /**
     * Holds an instance of the User model
     *
     * @var User
     */
    protected $user;

    /**
     * @var Tracert
     */
    protected $tracert;

    /**
     * Construct the class
     *
     * @param User $user
     * @param Guard $auth
     * @param Tracert $tracert
     */
    public function __construct(User $user, Guard $auth, Tracert $tracert)
    {
        parent::__construct($auth);

        $this->middleware('IsOwner', ['only', 'edit'] );

        $this->user = $user;
        $this->tracert = $tracert;
    }

    ///////////////////////////////////////////////////////////////////////////
    // View methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Show the view to edit a given profile
     *
     * @param $hash
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
     * Update the profile of the given user
     *
     * @param $hash
     * @param ProfileUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($hash, ProfileUpdateRequest $request)
    {
        $user = $this->user->byHash($hash);
        $user->name = $request->name;
        $user->firstname = $request->firstname;
        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->has('newpassword')) $user->password = $request->newpassword;

        if ($request->hasFile('profilepicture')) $this->handleImage($request->file('profilepicture'), $user);

        $user->save();

        $this->tracert->log('users', $user->id, $this->auth_user->id, 'update');

        $message = trans('blogify::notify.success', [
            'model' => 'User', 'name' => $user->fullName, 'action' =>'updated'
        ]);
        session()->flash('notify', ['success', $message] );

        return redirect()->route('admin.dashboard');
    }

    ///////////////////////////////////////////////////////////////////////////
    // Helper methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Call the functions to handle the profile picture
     *
     * @param $image
     * @param $user
     */
    private function handleImage($image, $user)
    {
        $filename = $this->generateFilename();
        $path = $this->resizeAndSaveProfilePicture( $image, $filename );

        if (isset($user->profilepicture)) $this->removeOldPicture($user->profilepicture);

        $user->profilepicture = $path;
    }

    /**
     * Generate a file name for the profile picture
     *
     * @return string
     */
    private function generateFilename()
    {
        return time() . '-' . $this->auth_user->username . '-profilepicture';
    }

    /**
     * Resize and save the profile picture
     *
     * @param $image
     * @param $filename
     * @return string
     */
    private function resizeAndSaveProfilePicture($image, $filename)
    {
        $extention = $image->getClientOriginalExtension();
        $fullpath = $this->config->upload_paths->profiles->profilepictures . $filename . '.' . $extention;

        Image::make($image->getRealPath())
            ->resize($this->config->image_sizes->profilepictures[0], $this->config->image_sizes->profilepictures[1] , function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($fullpath);

        return $fullpath;
    }

    /**
     * Remove the old profile picture
     * from the server
     *
     * @param $oldPicture
     */
    private function removeOldPicture($oldPicture)
    {
        if (file_exists($oldPicture)) unlink($oldPicture);
    }

}