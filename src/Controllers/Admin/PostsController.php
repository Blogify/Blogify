<?php

namespace jorenvanhocht\Blogify\Controllers\Admin;

use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Hashing\Hasher;
use jorenvanhocht\Blogify\Blogify;
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
use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Tracert\Tracert;

class PostsController extends BaseController
{

    /**
     * @var \jorenvanhocht\Blogify\Models\Post
     */
    protected $post;

    /**
     * @var \jorenvanhocht\Blogify\Models\Status
     */
    protected $status;

    /**
     * @var \jorenvanhocht\Blogify\Models\Visibility
     */
    protected $visibility;

    /**
     * @var \App\User
     */
    protected $user;

    /**
     * @var \jorenvanhocht\Blogify\Models\Category
     */
    protected $category;

    /**
     * @var \jorenvanhocht\Blogify\Models\Tag
     */
    protected $tag;

    /**
     * @var \jorenvanhocht\Blogify\Models\Role
     */
    protected $role;

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
     * @var \jorenvanhocht\Blogify\Services\BlogifyMailer
     */
    protected $mail;

    /**
     * @var \Illuminate\Contracts\Cache\Repository;
     */
    protected $cache;

    /**
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hash;

    /**
     * @var \jorenvanhocht\Blogify\Blogify
     */
    protected $blogify;

    /**
     * @var \jorenvanhocht\Tracert\Tracert
     */
    protected $tracert;

    /**
     * @param \jorenvanhocht\Blogify\Models\Tag $tag
     * @param \jorenvanhocht\Blogify\Models\Role $role
     * @param \App\User $user
     * @param \jorenvanhocht\Blogify\Models\Post $post
     * @param \jorenvanhocht\Blogify\Services\BlogifyMailer $mail
     * @param \Illuminate\Contracts\Hashing\Hasher $hash
     * @param \jorenvanhocht\Blogify\Models\Status $status
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @param \jorenvanhocht\Blogify\Models\Category $category
     * @param \jorenvanhocht\Blogify\Models\Visibility $visibility
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @param \jorenvanhocht\Blogify\Blogify $blogify
     * @param \jorenvanhocht\Tracert\Tracert $tracert
     */
    public function __construct(
        Tag $tag,
        Role $role,
        User $user,
        Post $post,
        BlogifyMailer $mail,
        Hasher $hash,
        Status $status,
        Repository $cache,
        Category $category,
        Visibility $visibility,
        Guard $auth,
        Blogify $blogify,
        Tracert $tracert
    ) {
        parent::__construct($auth);

        $this->appendMiddleware();

        $this->tag = $tag;
        $this->role = $role;
        $this->user = $user;
        $this->post = $post;
        $this->mail = $mail;
        $this->hash = $hash;
        $this->cache = $cache;
        $this->status = $status;
        $this->blogify = $blogify;
        $this->tracert = $tracert;
        $this->category = $category;
        $this->visibility = $visibility;
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
        $scope = 'for'.$this->auth_user->role->name;
        $data = [
            'posts' => (! $trashed) ?
                $this->post->$scope()
                        ->orderBy('publish_date', 'DESC')
                        ->paginate($this->config->items_per_page)
                :
                $this->post->$scope()
                        ->onlyTrashed()
                        ->orderBy('publish_date', 'DESC')
                        ->paginate($this->config->items_per_page),
            'trashed' => $trashed,
        ];

        return view('blogify::admin.posts.index', $data);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $hash = $this->auth_user->hash;
        $post = $this->cache->has("autoSavedPost-$hash") ? $this->buildPostObject() : null;
        $data = $this->getViewData($post);

        return view('blogify::admin.posts.form', $data);
    }

    /**
     * @param string $hash
     * @return \Illuminate\View\View
     */
    public function show($hash)
    {
        $data = [
            'post' => $this->post->byHash($hash),
        ];

        if ($data['post']->count() <= 0) {
            abort(404);
        }

        return view('blogify::admin.posts.show', $data);
    }

    /**
     * @param string $hash
     * @return \Illuminate\View\View
     */
    public function edit($hash)
    {
        $originalPost = $this->post->byHash($hash);
        $hash = $this->auth_user->hash;
        $post = $this->cache->has("autoSavedPost-$hash") ? $this->buildPostObject() : $originalPost;
        $data = $this->getViewData($post);

        $originalPost->being_edited_by = $this->auth_user->id;
        $originalPost->save();

        return view('blogify::admin.posts.form', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    // CRUD methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Store or update a post
     *
     * @param \jorenvanhocht\Blogify\Requests\PostRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PostRequest $request)
    {
        $this->data = objectify($request->except([
            '_token', 'newCategory', 'newTags'
        ]));

        if (! empty($this->data->tags)) {
            $this->buildTagsArray();
        }

        $post = $this->storeOrUpdatePost();

        if ($this->status->byHash($this->data->status)->name == 'Pending review') {
            $this->mailReviewer($post);
        }

        $action = ($request->hash == '') ? 'created' : 'updated';

        $this->tracert->log('posts', $post->id, $this->auth_user->id, $action);

        $message = trans('blogify::notify.success', [
            'model' => 'Post', 'name' => $post->title, 'action' => $action
        ]);
        session()->flash('notify', ['success', $message]);

        $hash = $this->auth_user->hash;
        $this->cache->forget("autoSavedPost-$hash");

        return redirect()->route('admin.posts.index');
    }

    /**
     * @param string $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($hash)
    {
        $post = $this->post->byHash($hash);
        $post->delete();

        $this->tracert->log('posts', $post->id, $this->auth_user->id, 'delete');

        $message = trans('blogify::notify.success', [
            'model' => 'Post', 'name' => $post->title, 'action' =>'deleted'
        ]);
        session()->flash('notify', ['success', $message]);

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
     * @param \jorenvanhocht\Blogify\Requests\ImageUploadRequest $request
     * @return string
     */
    public function uploadImage(ImageUploadRequest $request)
    {
        $image_name = $this->resizeAndSaveImage($request->file('upload'));
        $path = config('app.url').'/uploads/posts/'.$image_name;
        $func = $request->get('CKEditorFuncNum');
        $result = "<script>window.parent.CKEDITOR.tools.callFunction($func, '$path', 'Image has been uploaded')</script>";

        return $result;
    }

    /**
     * Cancel changes in a post
     * and set being_edited_by
     * back to null
     *
     * @param string $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel($hash = null)
    {
        if (! isset($hash)) {
            return redirect()->route('admin.posts.index');
        }

        $userHash = $this->auth_user->hash;
        if ($this->cache->has("autoSavedPost-$userHash")) {
            $this->cache->forget("autoSavedPost-$userHash");
        }

        $post = $this->post->byHash($hash);
        $post->being_edited_by = null;
        $post->save();

        $this->tracert->log('posts', $post->id, $this->auth_user->id, 'canceled');

        $message = trans('blogify::notify.success', [
            'model' => 'Post', 'name' => $post->name, 'action' =>'canceled'
        ]);
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.posts.index');
    }

    /**
     * @param string $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($hash)
    {
        $post = $this->post->withTrashed()->byHash($hash);
        $post->restore();

        $message = trans('blogify::notify.success', [
            'model' => 'Post', 'name' => $post->title, 'action' =>'restored'
        ]);
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.posts.index');
    }

    ///////////////////////////////////////////////////////////////////////////
    // Helper methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @return void
     */
    private function appendMiddleware()
    {
        $this->middleware('HasAdminOrAuthorRole', [
            'only' => ['create'],
        ]);

        $this->middleware('CanEditPost', [
            'only' => ['edit'],
        ]);

        $this->middleware('DenyIfBeingEdited', [
            'only' => ['edit'],
        ]);

        $this->middleware('CanViewPost', [
            'only' => ['edit', 'show'],
        ]);
    }

    /**
     * Get the default data for the
     * create and edit view
     *
     * @param $post
     * @return array
     */
    private function getViewData($post = null)
    {
        return [
            'reviewers'     => $this->user->reviewers(),
            'statuses'      => $this->status->all(),
            'categories'    => $this->category->all(),
            'visibility'    => $this->visibility->all(),
            'publish_date'  => Carbon::now()->format('d-m-Y H:i'),
            'post'          => $post,
        ];
    }

    /**
     * @param $image
     * @return string
     */
    private function resizeAndSaveImage($image)
    {
        $image_name = $this->createImageName();
        $fullpath = $this->createFullImagePath($image_name, $image->getClientOriginalExtension());

        Image::make($image->getRealPath())
            ->resize($this->config->image_sizes->posts[0], null, function($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($fullpath);

        return $image_name.'.'.$image->getClientOriginalExtension();
    }

    /**
     * @param string $image_name
     * @param $extension
     * @return string
     */
    private function createFullImagePath($image_name, $extension)
    {
        return public_path($this->config->upload_paths->posts->images.$image_name.'.'.$extension);
    }

    /**
     * @return string
     */
    private function createImageName()
    {
        return time().'-'.str_replace(' ', '-', $this->auth_user->fullName);
    }

    /**
     * @return void
     */
    private function buildTagsArray()
    {
        $tags = explode(',', $this->data->tags);

        foreach ($tags as $hash) {
            array_push($this->tags, $this->tag->byHash($hash)->id);
        }
    }

    /**
     * @return \jorenvanhocht\Blogify\Models\Post
     */
    private function storeOrUpdatePost()
    {
        if (! empty($this->data->hash)) {
            $post = $this->post->byHash($this->data->hash);
        } else {
            $post = new Post;
            $post->hash = $this->blogify->makeHash('posts', 'hash', true);
        }

        $post->slug = $this->data->slug;
        $post->title = $this->data->title;
        $post->content = $this->data->post;
        $post->status_id = $this->status->byHash($this->data->status)->id;
        $post->publish_date = $this->data->publishdate;
        $post->user_id = $this->user->byHash($this->auth_user->hash)->id;
        $post->reviewer_id = $this->user->byHash($this->data->reviewer)->id;
        $post->visibility_id = $this->visibility->byHash($this->data->visibility)->id;
        $post->category_id = $this->category->byHash($this->data->category)->id;
        $post->being_edited_by = null;

        if (!empty($this->data->password)) {
            $post->password = $this->hash->make($this->data->password);
        }

        $post->save();
        $post->tag()->sync($this->tags);

        return $post;
    }

    /**
     * @param \jorenvanhocht\Blogify\Models\Post $post
     * @return void
     */
    private function mailReviewer($post)
    {
        $reviewer = $this->user->find($post->reviewer_id);
        $data = [
            'reviewer'  => $reviewer,
            'post'      => $post,
        ];

        $this->mail->mailReviewer($reviewer->email, 'An article needs your expertise', $data);
    }

    /**
     * Build a post object when there
     * is a cached post so we can put
     * the data back in the form
     *
     * @return object
     */
    private function buildPostObject()
    {
        $hash = $this->auth_user->hash;
        $cached_post = $this->cache->get("autoSavedPost-$hash");

        $post = [];
        $post['hash'] = '';
        $post['title'] = $cached_post['title'];
        $post['slug'] = $cached_post['slug'];
        $post['content'] = $cached_post['content'];
        $post['publish_date'] = $cached_post['publishdate'];
        $post['status_id'] = $this->status->byHash($cached_post['status'])->id;
        $post['visibility_id'] = $this->visibility->byHash($cached_post['visibility'])->id;
        $post['reviewer_id'] = $this->user->byHash($cached_post['reviewer'])->id;
        $post['category_id'] = $this->category->byHash($cached_post['category'])->id;
        $post['tag'] = $this->buildTagsArrayForPostObject($cached_post['tags']);

        return objectify($post);
    }

    /**
     * @param $tags
     * @return array
     */
    private function buildTagsArrayForPostObject($tags)
    {
        if ($tags == "") {
            return [];
        }

        $aTags = [];
        $hashes = explode(',', $tags);

        foreach ($hashes as $tag) {
            array_push($aTags, $this->tag->byHash($tag));
        }

        return $aTags;
    }
}