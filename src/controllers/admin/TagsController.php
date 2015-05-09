<?php namespace jorenvanhocht\Blogify\Controllers\admin;

use Illuminate\Support\Facades\Config;
use Input;
use jorenvanhocht\Blogify\Models\Tag;
use jorenvanhocht\Blogify\Requests\TagUpdateRequest;
use Request;

class TagsController extends BaseController {

    /**
     * Holds an instance of the Tag model
     *
     * @var Tag
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
     * Construct the class
     *
     * @param Tag $tag
     */
    public function __construct( Tag $tag )
    {
        parent::__construct();

        $this->tag      = $tag;
    }

    ///////////////////////////////////////////////////////////////////////////
    // View methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Show the view with active/deleted tags
     *
     * @param null $trashed
     * @return \Illuminate\View\View
     */
    public function index( $trashed = null )
    {
        $data = [
            'tags' => ( ! $trashed ) ? $this->tag->orderBy('created_at', 'DESC')->paginate( $this->config->items_per_page ) : $this->tag->onlyTrashed()->orderBy('created_at', 'DESC')->paginate( $this->config->items_per_page ),
            'trashed' => $trashed
        ];

        return view('blogify::admin.tags.index', $data);
    }

    /**
     * Show the view to create new tag(s)
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('blogify::admin.tags.form');
    }

    /**
     * Show the view to edit a given tag
     *
     * @param $hash
     * @return \Illuminate\View\View
     */
    public function edit( $hash )
    {
        $data = [
            'tag' => $this->tag->byHash( $hash )
        ];

        return view('blogify::admin.tags.form', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    // CRUD methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Store or update tag(s)
     *
     * @return $this|array|\Illuminate\Http\RedirectResponse
     */
    public function storeOrUpdate()
    {
        // prepare submitted tag(s)
        $this->fillTagsArray();
        $this->deleteSpacesAtTheBeginningAndEnd();

        // validate tag(s)
        $validation = $this->tag->validate($this->tags);
        if ( $validation->fails() )
        {
            $data = [
                'passed' => false,
                'messages' => $validation->messages(),
            ];

            if ( Request::ajax() ) return $data;

            return redirect()->back()->withErrors( $validation->messages() )->withInput();
        }

        // store or update the tag in the db
        $this->storeOrUpdateTags();

        $data = [ 'passed' => true, 'tags'  => $this->stored_tags ];
        if (  Request::ajax() ) return $data;

        $message = trans('blogify::notify.success', ['model' => 'Tags', 'name' => $this->getTagNames(), 'action' =>'created']);
        session()->flash('notify', [ 'success', $message ] );

        return redirect()->route('admin.tags.index');
    }

    /**
     * Update an given tag
     *
     * @param $hash
     * @param TagUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update( $hash, TagUpdateRequest $request )
    {
        $tag        = $this->tag->byHash( $hash );
        $tag->name  = $request->tags;
        $tag->save();

        $message    = trans('blogify::notify.success', ['model' => 'Tags', 'name' => $tag->name, 'action' =>'updated']);
        session()->flash('notify', [ 'success', $message ] );

        return redirect()->route('admin.tags.index');
    }

    /**
     * Delete a given hash
     *
     * @param $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy( $hash )
    {
        $tag        = $this->tag->byHash( $hash );
        $tag_name   = $tag->name;
        $tag->delete();

        $message    = trans('blogify::notify.success', ['model' => 'Tags', 'name' => $tag_name, 'action' =>'deleted']);
        session()->flash('notify', [ 'success', $message ] );

        return redirect()->route('admin.tags.index');
    }

    ///////////////////////////////////////////////////////////////////////////
    // Helper methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Fill the global tags array
     *
     */
    private function fillTagsArray()
    {
        $tags = Input::get('tags');
        $this->tags = explode(',', $tags);
    }

    /**
     * Delete the spaces at the
     * beginning or at the and
     * of a tag
     *
     */
    private function deleteSpacesAtTheBeginningAndEnd()
    {
        foreach ( $this->tags as $key => $tag )
        {
            $this->tags[$key] = trim($tag);
        }
    }

    /**
     * Store or update the tag(s)
     * in the db
     *
     */
    private function storeOrUpdateTags()
    {
        foreach ( $this->tags as $tag_name )
        {
            $t = $this->tag->whereName($tag_name)->first();

            if ( count( $t ) > 0 )
            {
                $tag = $t;
            }
            else
            {
                $tag        = new Tag;
                $tag->hash  = blogify()->makeUniqueHash('tags', 'hash');
            }

            $tag->name = $tag_name;

            $tag->save();
            array_push($this->stored_tags, $tag);
        }
    }

    /**
     * Get the names of the tags
     * that have been added
     *
     * @return string
     */
    private function getTagNames()
    {
        $tags = '';

        foreach ( $this->stored_tags as $tag )
        {
            $tags .= $tag->name . ', ';
        }

        return $tags;
    }
}