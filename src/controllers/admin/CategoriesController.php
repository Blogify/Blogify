<?php namespace jorenvanhocht\Blogify\Controllers\admin;

use jorenvanhocht\Blogify\Models\Category;
use jorenvanhocht\Blogify\Requests\CategoryRequest;
use Request;

class CategoriesController extends BlogifyController {

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

    ///////////////////////////////////////////////////////////////////////////
    // CRUD methods
    ///////////////////////////////////////////////////////////////////////////

    public function store( CategoryRequest $request )
    {
        $category = $this->storeOrUpdateCategory( $request );

        if ( Request::ajax() ) return $category;

        return '';
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