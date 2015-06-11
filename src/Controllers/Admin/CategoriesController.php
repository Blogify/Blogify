<?php

namespace jorenvanhocht\Blogify\Controllers\Admin;

use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Blogify\Blogify;
use jorenvanhocht\Blogify\Models\Category;
use jorenvanhocht\Blogify\Requests\CategoryRequest;
use jorenvanhocht\Tracert\Tracert;

class CategoriesController extends BaseController
{

    /**
     * @var \jorenvanhocht\Blogify\Models\Category
     */
    protected $category;

    /**
     * @var \jorenvanhocht\Blogify\Blogify
     */
    protected $blogify;

    /**
     * @var \jorenvanhocht\Tracert\Tracert
     */
    protected $tracert;

    /**
     * @param \jorenvanhocht\Blogify\Models\Category $category
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @param \jorenvanhocht\Blogify\Blogify $blogify
     * @param \jorenvanhocht\Tracert\Tracert $tracert
     */
    public function __construct(
        Category $category,
        Guard $auth,
        Blogify $blogify,
        Tracert $tracert
    ) {
        parent::__construct($auth);

        $this->category = $category;
        $this->blogify = $blogify;
        $this->tracert = $tracert;
    }

    ///////////////////////////////////////////////////////////////////////////
    // View methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @param $trashed
     * @return \Illuminate\View\View
     */
    public function index($trashed = null)
    {
        $categories = (! $trashed) ?
            $this->category
                ->orderBy('created_at', 'DESC')
                ->paginate($this->config->items_per_page)
            :
            $this->category
                ->onlyTrashed()
                ->orderBy('created_at', 'DESC')
                ->paginate($this->config->items_per_page);

        return view('blogify::admin.categories.index', compact('categories', 'trashed'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('blogify::admin.categories.form');
    }

    /**
     * @param string $hash
     * @return \Illuminate\View\View
     */
    public function edit($hash)
    {
        $category = $this->category->byHash($hash);

        return view('blogify::admin.categories.form', compact('category'));
    }

    ///////////////////////////////////////////////////////////////////////////
    // CRUD methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @param \jorenvanhocht\Blogify\Requests\CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryRequest $request)
    {
        $category = $this->storeOrUpdateCategory($request);

        $this->tracert->log('categories', $category->id, $this->auth_user->id);

        if ($request->ajax()) {
            return $category;
        }

        $message = trans(
            'blogify::notify.success', [
                'model' => 'Category',
                'name' => $category->name,
                'action' =>'created'
            ]
        );
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.categories.index');
    }

    /**
     * @param string $hash
     * @param \jorenvanhocht\Blogify\Requests\CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($hash, CategoryRequest $request)
    {
        $category = $this->category->byHash($hash);
        $category->name = $request->name;
        $category->save();

        $this->tracert->log(
            'categories',
            $category->id,
            $this->auth_user->id,
            'update'
        );

        $message = trans(
            'blogify::notify.success', [
                'model' => 'Category',
                'name' => $category->name,
                'action' =>'updated'
            ]
        );
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.categories.index');
    }

    /**
     * @param string $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($hash)
    {
        $category = $this->category->byHash($hash);
        $category_name = $category->name;
        $category->delete();

        $this->tracert->log(
            'categories',
            $category->id,
            $this->auth_user->id,
            'delete'
        );

        $message = trans(
            'blogify::notify.success', [
                'model' => 'Categorie',
                'name' => $category_name,
                'action' =>'deleted'
            ]
        );
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.categories.index');
    }

    /**
     * @param string $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($hash)
    {
        $category = $this->category->withTrashed()->byHash($hash);
        $category_name = $category->name;
        $category->restore();

        $message = trans(
            'blogify::notify.success', [
                'model' => 'Category',
                'name' => $category_name,
                'action' =>'restored'
            ]
        );
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.categories.index');
    }

    ///////////////////////////////////////////////////////////////////////////
    // Helper methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Save the given category in the db
     *
     * @param CategoryRequest $request
     * @return \jorenvanhocht\Blogify\Models\Category
     */
    private function storeOrUpdateCategory($request)
    {
        $cat = $this->category->whereName($request->name)->first();

        if (count($cat) > 0) {
            $category = $cat;
        } else {
            $category = new Category;
            $category->hash = $this->blogify->makeHash('categories', 'hash', true);
        }

        $category->name = $request->name;
        $category->save();

        return $category;
    }

}