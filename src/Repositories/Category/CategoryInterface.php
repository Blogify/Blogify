<?php

namespace jorenvanhocht\Blogify\Repositories\Category;

interface CategoryInterface
{

    public function orderBy($column, $order);
    public function paginate($perPage, $columns, $pageName);
    public function onlyTrashed();
    public function byHash($hash);

}