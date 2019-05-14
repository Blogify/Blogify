<?php

namespace jorenvanhocht\Blogify\Controllers\Admin;

use Input;
use jorenvanhocht\Blogify\Blogify;
use jorenvanhocht\Blogify\Models\Tag;
use jorenvanhocht\Blogify\Requests\TagUpdateRequest;
use jorenvanhocht\Tracert\Tracert;
use Request;
use Illuminate\Contracts\Auth\Guard;

class TagsController extends BaseController
{

    /**
     * @var \jorenvanhocht\Blogify\Models\Tag
     */
    protected $tag;

    /**
     * Holds the submitted tags
     *
     * @var array
     */
    protected $tags = [];

    /**
     * Hols the tags that are successfully added
     *
     * @var array
     */
    protected $stored_tags = [];

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
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @param \jorenvanhocht\Blogify\Blogify $blogify
     * @param \jorenvanhocht\Tracert\Tracert $tracert
     */
    public function __construct(
        Tag $tag,
        Guard $auth,
        Blogify $blogify,
        Tracert $tracert
    ) {
        parent::__construct($auth);

        $this->tag = $tag;
        $this->blogify = $blogify;
        $this->tracert = $tracert;
    }

    ///////////////////////////////////////////////////////////////////////////
    // View methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @param string $trashed
     * @return \Illuminate\View\View
     */
    public function index($trashed = null)
    {
        $data = [
            'tags' => (! $trashed) ?
                $this->tag->orderBy('created_at', 'DESC')
                    ->paginate($this->config->items_per_page)
                :
                $this->tag->onlyTrashed()
                    ->orderBy('created_at', 'DESC')
                    ->paginate($this->config->items_per_page),
            'trashed' => $trashed,
        ];

        return view('blogify::admin.tags.index', $data);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('blogify::admin.tags.form');
    }

    /**
     * @param $hash
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $data = [
            //'tag' => $this->tag->byHash($hash),
            'tag' => $this->tag->find($id),
        ];

        return view('blogify::admin.tags.form', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    // CRUD methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @return $this|array|\Illuminate\Http\RedirectResponse
     */
    public function storeOrUpdate(TagUpdateRequest $request)
    {
        // prepare submitted tag(s)
        $this->fillTagsArray();
        $this->deleteSpacesAtTheBeginningAndEnd();

        // validate tag(s)
        $validation = $this->tag->validate($this->tags);
        if ($validation->fails()) {
            $data = [
                'passed' => false,
                'messages' => $validation->messages(),
            ];

            if (Request::ajax()) {
                return $data;
            }

            return redirect()->back()->withErrors($validation->messages())->withInput();
        }

        // store or update the tag in the db
        $this->storeOrUpdateTags($request);

        $data = ['passed' => true, 'tags' => $this->stored_tags];
        if (Request::ajax()) {
            return $data;
        }

        $message = trans('blogify::notify.success', [
            'model' => 'Tags',
            'name' => $this->getTagNames(),
            'action' =>'created'
        ]);
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.tags.index');
    }

    /**
     * @param string $hash
     * @param \jorenvanhocht\Blogify\Requests\TagUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, TagUpdateRequest $request)
    {
        //$tag = $this->tag->byHash($hash);
        $tag = $this->tag->find($id);
        $tag->name = $request->tags;
        $tag->slug = $request->slug;
        $tag->meta_title = $request->meta_title;
        $tag->meta_description = $request->meta_description;
        $tag->save();

        //$this->tracert->log('tags', $tag->id, $this->auth_user->id, 'update');

        $message = trans('blogify::notify.success', [
            'model' => 'Tags', 'name' => $tag->name, 'action' =>'updated'
        ]);
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.tags.index');
    }

    /**
     * @param string $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        //$tag = $this->tag->byHash($hash);
        $tag = $this->tag->find($id);
        $tag->delete();

        //$this->tracert->log('tags', $tag->id, $this->auth_user->id, 'delete');

        $message = trans('blogify::notify.success', [
            'model' => 'Tags', 'name' => $tag->name, 'action' =>'deleted'
        ]);
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.tags.index');
    }

    /**
     * @param string $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        //$tag = $this->tag->withTrashed()->byHash($hash);
        $tag = $this->tag->withTrashed()->find($id);
        $tag->restore();

        $message = trans('blogify::notify.success', [
            'model' => 'Tag', 'name' => $tag->name, 'action' =>'restored'
        ]);
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.tags.index');
    }

    ///////////////////////////////////////////////////////////////////////////
    // Helper methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @return void
     */
    private function fillTagsArray()
    {
        $tags = Input::get('tags');
        $this->tags = explode(',', $tags);
    }

    /**
     * @return void
     */
    private function deleteSpacesAtTheBeginningAndEnd()
    {
        foreach ($this->tags as $key => $tag) {
            $this->tags[$key] = trim($tag);
        }
    }

    /**
     * @return void
     */
    private function storeOrUpdateTags($request)
    {
        /*foreach ($this->tags as $tag_name) {
            $t = $this->tag->whereName($tag_name)->first();

            if (count($t) > 0) {
                $tag = $t;
            } else {
                $tag = new Tag;
                //$tag->hash = $this->blogify->makeHash('blogify_tags', 'hash', true);
            }

            $tag->name = $tag_name;

            $tag->save();
            array_push($this->stored_tags, $tag);
            //$this->tracert->log('tags', $tag->id, $this->auth_user->id);
        }*/

        $t = $this->tag->whereName($request->tags)->first();

        if (count($t) > 0) {
            $tag = $t;
        } else {
            $tag = new Tag;
        }

        $tag->name = $request->tags;
        $tag->slug = $request->slug;
        $tag->meta_title = $request->meta_title;
        $tag->meta_description = $request->meta_description;
        $tag->save();

        return $tag;
    }

    /**
     * @return string
     */
    private function getTagNames()
    {
        $tags = '';

        foreach ($this->stored_tags as $tag) {
            $tags .= $tag->name.', ';
        }

        return $tags;
    }
}