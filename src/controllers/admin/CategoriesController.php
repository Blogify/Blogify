<?php namespace jorenvanhocht\Blogify\Controllers\admin;

use jorenvanhocht\Blogify\Models\Category;
use jorenvanhocht\Blogify\Requests\CategoryRequest;
use Request;

class CategoriesController extends BaseController {

    /**
     * Holds an instance of the Category model
     *
     * @var Category
     */
    protected $category;

    public function __construct( Category $category )
    {
        parent::__construct();

        $this->category = $category;
    }

    ///////////////////////////////////////////////////////////////////////////
    // View methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Show the view with all active/trashed
     * categories
     *
     * @param null $trashed
     * @return \Illuminate\View\View
     */
    public function index( $trashed = null )
    {
        $data = [
            'categories' => ( ! $trashed ) ? $this->category->orderBy('created_at', 'DESC')->paginate( $this->config->items_per_page ) : $this->category->onlyTrashed()->orderBy('created_at', 'DESC')->paginate( $this->config->items_per_page ),
            'trashed' => $trashed
        ];

        return view('blogify::admin.categories.index', $data);
    }

    /**
     * Show the view to create a new category
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('blogify::admin.categories.form');
    }

    /**
     * Show the view to edit a given
     * category
     *
     * @param $hash
     * @return \Illuminate\View\View
     */
    public function edit( $hash )
    {
        $data = [
            'category' => $this->category->byHash( $hash )
        ];

        return view('blogify::admin.categories.form', $data);
    }

    ///////////////////////////////////////////////////////////////////////////
    // CRUD methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Store a new category
     *
     * @param CategoryRequest $request
     * @return Category|string
     */
    public function store( CategoryRequest $request )
    {
        $category = $this->storeOrUpdateCategory( $request );

        if ( $request->ajax() ) return $category;

        $message    = trans('blogify::notify.success', ['model' => 'Category', 'name' => $category->name, 'action' =>'created']);
        session()->flash('notify', [ 'success', $message ] );

        return redirect()->route('admin.categories.index');
    }

    /**
     * Update a given category
     *
     * @param $hash
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update ( $hash, CategoryRequest $request )
    {
        $category = $this->category->byHash( $hash );
        $category->name = $request->name;
        $category->save();

        $message    = trans('blogify::notify.success', ['model' => 'Category', 'name' => $category->name, 'action' =>'updated']);
        session()->flash('notify', [ 'success', $message ] );

        return redirect()->route('admin.categories.index');
    }

    /**
     * Delete a given category
     *
     * @param $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy( $hash )
    {
        $category        = $this->category->byHash( $hash );
        $category_name   = $category->name;
        $category->delete();

        $message    = trans('blogify::notify.success', ['model' => 'Tags', 'name' => $category_name, 'action' =>'deleted']);
        session()->flash('notify', [ 'success', $message ] );

        return redirect()->route('admin.categories.index');
    }

    ///////////////////////////////////////////////////////////////////////////
    // Helper methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Save the given category in the db
     *
     * @param $request
     * @return Category
     */
    private function storeOrUpdateCategory( $request )
    {
        $cat                = $this->category->whereName( $request->name )->first();

        if ( count($cat) > 0 )
        {
            $category       = $cat;
        }
        else
        {
            $category       = new Category;
            $category->hash = blogify()->makeUniqueHash('categories', 'hash');
        }

        $category->name     = $request->name;
        $category->save();

        return $category;
    }

}