<?php
namespace jorenvanhocht\Blogify\Controllers\admin;

use App\Http\Controllers\Controller;
use DB;
use Request;
use Response;

class ApiController extends Controller{

    public function sort( $table, $column, $order, $trashed = false )
    {
        if ( $trashed )
        {
            $data = DB::table($table)
                ->whereNotNull('deleted_at')
                ->orderBy($column, $order)->get();
        }
        else
        {
            $data = DB::table($table)
                ->whereNull('deleted_at')
                ->orderBy($column, $order)->get();
        }


        if ( Request::ajax() )
        {
            return Response::json( ['data' => $data]);
        }

        return json_encode($data);
    }

}