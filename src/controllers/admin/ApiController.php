<?php namespace jorenvanhocht\Blogify\Controllers\admin;

use DB;

class ApiController extends BlogifyController {

    /**
     * Holds an instance of
     * the Blogify config file
     *
     * @var object
     */
    protected $config;

    public function __construct()
    {
        parent::__construct();

        $this->config = objectify( config()->get('blogify') );
    }

    /**
     * Order the data of a given table on the given column
     * and the given order
     *
     * @param $table
     * @param $column
     * @param $order
     * @param bool $trashed
     * @return string
     */
    public function sort( $table, $column, $order, $trashed = false )
    {
        $data = DB::table( $table );

        // Check for trashed data
        $data = $trashed ? $data->whereNotNull('deleted_at') : $data->whereNull('deleted_at');

        $data = $data->orderBy($column, $order)->paginate( $this->config->items_per_page );

        return $data;
    }

}