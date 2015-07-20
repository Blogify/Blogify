<?php

namespace jorenvanhocht\Blogify\Repositories\Category;

use jorenvanhocht\Blogify\Models\Category;

class EloquentCategoryRepository implements CategoryInterface
{

    /**
     * @var \jorenvanhocht\Blogify\Models\Category
     */
    protected $category;

    /**
     * @param \jorenvanhocht\Blogify\Models\Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * @param $column
     * @param $order
     * @return mixed
     */
    function orderBy($column, $order)
    {
        return $this->category->orderBy($column, $order = 'asc');
    }

    /**
     * @param null $perPage
     * @param array $columns
     * @param string $pageName
     */
    public function paginate($perPage = null, $columns = array('*'), $pageName = 'page')
    {
        return $this->category->paginate($perPage, $columns, $pageName);
    }

    /**
     * @return void
     */
    public function onlyTrashed()
    {
        $this->category->onlyTrashed();
    }

    /**
     * @param $hash
     */
    public function byHash($hash)
    {
        return $this->category->byHash($hash);
    }
}

