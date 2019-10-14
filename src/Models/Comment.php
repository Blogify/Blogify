<?php

namespace jorenvanhocht\Blogify\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends BaseModel
{

    use SoftDeletes;

    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table = 'blogify_comments';

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

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function post()
    {
        return $this->belongsTo('jorenvanhocht\Blogify\Models\Post', 'post_id');
    }

    // Answer in response to parent
    public function parent()
    {
        return $this->belongsTo('jorenvanhocht\Blogify\Models\Comment', 'parent_id', 'id');
    }

    // Answers
    public function answers()
    {
        return $this->hasMany('jorenvanhocht\Blogify\Models\Comment', 'parent_id', 'id')
            ->orderBy('created_at', 'desc');
    }

    // Approved answers
    public function activeAnswers()
    {
        return $this->answers()->where('revised', 2);
    }

    // Unviewed answers
    public function unviewedAnswers()
    {
        return $this->activeAnswers()->whereNull('viewed');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    |
    | For more information pleas check out the official Laravel docs at
    | http://laravel.com/docs/5.0/eloquent#query-scopes
    |
    */

    public function scopeByRevised($query, $revised)
    {
        return $query->whereRevised($revised);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    | For more information pleas check out the official Laravel docs at
    | http://laravel.com/docs/5.0/eloquent#accessors-and-mutators
    |
    */

    public function getCreatedAtAttribute($value)
    {
        return date("d-m-Y H:i", strtotime($value));
    }

}