<?php namespace jorenvanhocht\Blogify\Controllers\admin;

use App\User;
use jorenvanhocht\Blogify\Models\Category;
use jorenvanhocht\Blogify\Models\Role;
use jorenvanhocht\Blogify\Models\Status;
use jorenvanhocht\Blogify\Models\Tag;
use jorenvanhocht\Blogify\Models\Visibility;
use jorenvanhocht\Blogify\Requests\ImageUploadRequest;
use Intervention\Image\Facades\Image;

class PostsController extends BlogifyController {

    /**
     * Holds an instance of the Status model
     *
     * @var Status
     */
    protected $status;

    /**
     * Holds an instance of the Visibility model
     *
     * @var Visibility
     */
    protected $visibility;

    /**
     * Holds an instance of the User model
     *
     * @var User
     */
    protected $user;

    /**
     * Holds an instance of the Category model
     *
     * @var Category
     */
    protected $category;

    /**
     * Holds an instance of the Tag model
     *
     * @var Tag
     */
    protected $tag;

    /**
     * Holds an instance of the Role model
     *
     * @var Role
     */
    protected $role;

    /**
     * Holds the configuration settings
     *
     * @var object
     */
    protected $config;

    public function __construct( Status $status, Visibility $visibility, User $user, Category $category, Tag $tag, Role $role )
    {
        parent::__construct();

        $this->config       = objectify( config()->get('blogify') );

        $this->tag          = $tag;
        $this->role         = $role;
        $this->user         = $user;
        $this->status       = $status;
        $this->category     = $category;
        $this->visibility   = $visibility;
    }

    ///////////////////////////////////////////////////////////////////////////
    // View methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Show the view with the overview off all posts
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('blogify::admin.posts.index');
    }

    /**
     * Show the view to create a new post
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $data = $this->getViewData();

        return view('blogify::admin.posts.form', $data);
    }

    /**
     * Show the view to edit a given post
     *
     * @param $hash
     * @return \Illuminate\View\View
     */
    public function edit( $hash )
    {
        return view('blogify::admin.posts.edit');
    }

    ///////////////////////////////////////////////////////////////////////////
    // CRUD methods
    ///////////////////////////////////////////////////////////////////////////

    public function uploadImage(ImageUploadRequest $request)
    {
        $image_name = $this->resizeAnsSaveImage( $request->file('upload') );
        $path       = config()->get('app.url').'/uploads/posts/' . $image_name;
        $func       = $_GET['CKEditorFuncNum'];
        $result     = "<script>window.parent.CKEDITOR.tools.callFunction($func, '$path', 'Image has been uploaded')</script>";

        return $result;
    }

    ///////////////////////////////////////////////////////////////////////////
    // Helper methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Get the default data for the
     * create and edit view
     *
     * @return array
     */
    public function getViewData()
    {
        $reviewer_role_id = $this->role->whereName('Reviewer')->first()->id;

        $data = [
            'reviewers'     => $this->user->byRole( $reviewer_role_id )->get(),
            'statuses'      => $this->status->all(),
            'categories'    => $this->category->all(),
            'visibility'    => $this->visibility->all(),
        ];

        return $data;
    }

    /**
     * Resize and save an uploaded image
     *
     * @param $image
     * @return string
     */
    public function resizeAnsSaveImage( $image )
    {
        $image_name = $this->createImageName();
        $fullpath   = $this->createFullImagePath( $image_name, $image->getClientOriginalExtension() );

        Image::make( $image->getRealPath() )
            ->resize( $this->config->image_sizes->posts[0], null , function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save( $fullpath );

        return $image_name . '.' . $image->getClientOriginalExtension();
    }

    /**
     * Generate the full path to the uploaded image
     *
     * @param $image_name
     * @param $extension
     * @return string
     */
    public function createFullImagePath( $image_name, $extension )
    {
        return public_path( $this->config->upload_paths->posts->images . $image_name . '.' . $extension );
    }

    /**
     * Generate a name for the uploaded image
     *
     * @return string
     */
    public function createImageName()
    {
        return time() . '-' . str_replace(' ', '-', $this->auth_user->fullName);
    }

}