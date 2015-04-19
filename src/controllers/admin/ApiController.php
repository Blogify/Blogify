<?php
namespace jorenvanhocht\Blogify\Controllers\admin;

use DB;
use Request;

class ApiController extends BlogifyController {

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

        $data = $data->orderBy($column, $order)->get();

        if ( Request::ajax() ) return response()->json( ['data' => $data]);

        return json_encode($data);
    }

}