<?php namespace jorenvanhocht\Blogify\Controllers\admin;

use App\User;
use Carbon\Carbon;
use jorenvanhocht\Blogify\Models\Category;
use jorenvanhocht\Blogify\Models\Role;
use jorenvanhocht\Blogify\Models\Status;
use jorenvanhocht\Blogify\Models\Tag;
use jorenvanhocht\Blogify\Models\Visibility;
use jorenvanhocht\Blogify\Requests\ImageUploadRequest;
use Intervention\Image\Facades\Image;
use jorenvanhocht\Blogify\Requests\PostRequest;
use jorenvanhocht\Blogify\Models\Post;
use jorenvanhocht\Blogify\Services\BlogifyMailer;
use Illuminate\Contracts\Cache\Repository;

class PostsController extends BaseController {

    /**
     * Holds an instance of the Post model
     *
     * @var Post
     */
    protected $post;

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

    /**
     * Holds the post data
     *
     * @var object
     */
    protected $data;

    /**
     * Holds all the tags that are
     * assigned to a post
     *
     * @var array
     */
    protected $tags = [];

    /**
     * Holds an instance of the BlogifyMailer class
     *
     * @var BlogifyMailer
     */
    protected $mail;

    /**
     * Holds an instance of the Cache contract
     *
     * @var Repository
     */
    protected $cache;

    public function __construct( Post $post, Status $status, Visibility $visibility, User $user, Category $category, Tag $tag, Role $role, BlogifyMailer $mail, Repository $cache )
    {
        parent::__construct();

        $this->middleware('posts.new.role.check', [
            'only' => ['create']
        ]);

        $this->middleware('posts.edit.denyIfBeingEdited', [
            'only' => ['edit']
        ]);

        $this->config       = objectify( config()->get('blogify') );

        $this->tag          = $tag;
        $this->role         = $role;
        $this->user         = $user;
        $this->post         = $post;
        $this->mail         = $mail;
        $this->cache        = $cache;
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
    public function index( $trashed = false )
    {
        $data = [
            'posts' => ( ! $trashed ) ? $this->post->paginate( $this->config->items_per_page ) : $this->post->onlyTrashed()->paginate( $this->config->items_per_page ),
            'trashed' => $trashed,
        ];
        return view('blogify::admin.posts.index', $data);
    }

    /**
     * Show the view to create a new post
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $hash = $this->auth_user->hash;
        $post = ( $this->cache->has("autoSavedPost-$hash") ) ? $this->buildPostObject() : null;
        $data = $this->getViewData( $post );

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

        $p      = $this->post->byHash($hash);
        $hash   = $this->auth_user->hash;
        $post   = ( $this->cache->has("autoSavedPost-$hash") ) ? $this->buildPostObject() : $p;
        $data   = $this->getViewData( $post );

        $p->being_edited_by = $this->auth_user->id;
        $p->save();

        return view('blogify::admin.posts.form', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    // CRUD methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Store a new post and call all
     * side functions
     *
     * @param PostRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store( PostRequest $request )
    {
        $this->data = objectify( $request->except(['_token', 'newCategory', 'newTags']) );

        if ( ! empty($this->data->tags) ) $this->buildTagsArray();

        $post = $this->storeOrUpdatePost();

        if ( $this->status->byHash( $this->data->status )->name == 'Pending review' ) $this->mailReviewer( $post );

        $message = trans('blogify::notify.success', ['model' => 'Post', 'name' => $post->title, 'action' => ( $request->hash == '' ) ? 'created' : 'updated']);
        session()->flash('notify', [ 'success', $message ] );
        $this->cache->forget('autoSavedPost');

        return redirect()->route('admin.posts.index');
    }

    /**
     * Delete a given post
     *
     * @param $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy( $hash )
    {
        $post       = $this->post->byHash( $hash );
        $name       = $post->title;

        $post->delete();

        $message = trans('blogify::notify.success', ['model' => 'Post', 'name' => $name, 'action' =>'deleted']);
        session()->flash('notify', [ 'success', $message ] );

        return redirect()->route('admin.posts.index');
    }

    /**
     * Function to upload images using
     * the SKEditor
     *
     * note: no CSRF protection on the route that is
     * calling this function because we are using the
     * CKEditor within an iframe :(
     *
     * @param ImageUploadRequest $request
     * @return string
     */
    public function uploadImage(ImageUploadRequest $request)
    {
        $image_name = $this->resizeAnsSaveImage( $request->file('upload') );
        $path       = config()->get('app.url').'/uploads/posts/' . $image_name;
        $func       = $request->get('CKEditorFuncNum');
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
     * @param $post
     * @return array
     */
    private function getViewData( $post = null )
    {
        $data               = [
            'reviewers'     => $this->user->reviewers(),
            'statuses'      => $this->status->all(),
            'categories'    => $this->category->all(),
            'visibility'    => $this->visibility->all(),
            'publish_date'  => Carbon::now()->format('d-m-Y H:i'),
            'post'          => $post,
        ];

        return $data;
    }

    /**
     * Resize and save an uploaded image
     *
     * @param $image
     * @return string
     */
    private function resizeAnsSaveImage( $image )
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
    private function createFullImagePath( $image_name, $extension )
    {
        return public_path( $this->config->upload_paths->posts->images . $image_name . '.' . $extension );
    }

    /**
     * Generate a name for the uploaded image
     *
     * @return string
     */
    private function createImageName()
    {
        return time() . '-' . str_replace(' ', '-', $this->auth_user->fullName);
    }

    /**
     * Separate the given tags and
     * store them separately in the
     * global array
     *
     */
    private function buildTagsArray()
    {
        $tags = explode(',', $this->data->tags);

        foreach ( $tags as $hash )
        {
            array_push($this->tags, $this->tag->byHash($hash)->id);
        }
    }

    /**
     * Store a new post or update an
     * given post in the DB
     *
     * @return Post
     */
    private function storeOrUpdatePost()
    {
        if ( !empty( $this->data->hash ) )
        {
            $post       = $this->post->byHash( $this->data->hash );
        }
        else
        {
            $post       = new Post;
            $post->hash = blogify()->makeUniqueHash('posts', 'hash');

        }

        $post->slug                 = $this->data->slug;
        $post->title                = $this->data->title;
        $post->short_description    = $this->data->short_description;
        $post->content              = $this->data->post;
        $post->status_id            = $this->status->byHash( $this->data->status )->id;
        $post->publish_date         = $this->data->publishdate;
        $post->user_id              = $this->user->byHash( $this->auth_user->hash )->id;
        $post->reviewer_id          = $this->user->byHash( $this->data->reviewer )->id;
        $post->visibility_id        = $this->visibility->byHash( $this->data->visibility )->id;
        $post->category_id          = $this->category->byHash($this->data->category)->id;
        $post->being_edited_by      = NULL;

        $post->save();
        $post->tag()->sync($this->tags);

        return $post;
    }

    /**
     * Send the assigned reviewer a
     * mail to notify him that he
     * is assigned
     *
     * @param $post
     */
    private function mailReviewer( $post )
    {
        $reviewer   = $this->user->find($post->reviewer_id);
        $data       = [
            'reviewer'  => $reviewer,
            'post'      => $post,
        ];

        $this->mail->mailReviewer( $reviewer->email, 'An article needs your expertise' , $data );
    }

    /**
     * Build a post object when there
     * is a cached post so we can put
     * the data back in the form
     *
     * @return mixed
     */
    private function buildPostObject()
    {
        $hash = $this->auth_user->hash;
        $cached_post    = $this->cache->get("autoSavedPost-$hash");

        $post                       = [];
        $post['hash']               = '';
        $post['title']              = $cached_post['title'];
        $post['slug']               = $cached_post['slug'];
        $post['short_description']  = $cached_post['short_description'];
        $post['content']            = $cached_post['content'];
        $post['publish_date']       = $cached_post['publishdate'];
        $post['status_id']          = $this->status->byHash( $cached_post['status'] )->id;
        $post['visibility_id']      = $this->visibility->byHash( $cached_post['visibility'] )->id;
        $post['reviewer_id']        = $this->user->ByHash( $cached_post['reviewer'] )->id;
        $post['category_id']        = $this->category->byHash( $cached_post['category'] )->id;
        $post['tag']                = $this->buildTagsArrayForPostObject( $cached_post['tags'] );

        return objectify($post);

    }

    /**
     * Build an array with all the
     * tags for a cached post
     *
     * @param $tags
     * @return array
     */
    private function buildTagsArrayForPostObject( $tags )
    {
        if ( $tags == "" ) return [];

        $t      = [];
        $tags   = explode(',', $tags);

        foreach ( $tags as $tag )
        {
            array_push($t, $this->tag->byHash($tag));
        }

        return $t;
    }
}