<?php

namespace jorenvanhocht\Blogify\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends BaseModel
{

    use SoftDeletes;

    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table = 'blogify_media';

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
    public $timestamps = true;

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
        return $this->belongsTo('jorenvanhocht\Blogify\Models\post', 'post_id');
    }


}