<?php

namespace jorenvanhocht\Blogify\Models;

class Status extends BaseModel
{

    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table = 'statuses';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * Set or unset the timestamps for the model
     *
     * @var bool
     */
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    | For more information pleas check out the official Laravel docs at
    | http://laravel.com/docs/5.0/eloquent#relationships
    |
    */

    public function post()
    {
        return $this->hasMany('jorenvanhocht\Blogify\Models\Post');
    }
}