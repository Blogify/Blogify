<?php

namespace jorenvanhocht\Blogify\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;

class Tag extends BaseModel
{

    use SoftDeletes;

    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table = 'blogify_tags';

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

    /**
     * @param $tags
     * @return \Illuminate\Validation\Validator
     */
    public function validate($tags)
    {
        $rules = [];
        $messages = [
            'required'  => trans('blogify::posts.validation.required'),
            'min'       => trans('blogify::posts.validation.min'),
            'max'       => trans('blogify::posts.validation.max'),
        ];

        foreach ($tags as $key => $tag) {
            $rules[$key] = 'required|min:2|max:45';
        }

        return Validator::make($tags, $rules, $messages);
    }

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
        return $this->belongsToMany('jorenvanhocht\Blogify\Models\Post', 'blogify_posts_have_tags', 'tag_id', 'post_id');
    }

}
