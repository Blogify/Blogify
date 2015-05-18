<?php namespace jorenvanhocht\Blogify\Models;

use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends BaseModel{

    use SoftDeletes;

    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table        = 'posts';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable     = [];

    /**
     * Set or unset the timestamps for the model
     *
     * @var bool
     */
    public $timestamps      = true;

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
        return $this->belongsTo('App\user');
    }

    public function comment()
    {
        return $this->hasMany('jorenvanhocht\Blogify\Models\comment');
    }

    public function category()
    {
        return $this->belongsTo('jorenvanhocht\Blogify\Models\category');
    }

    public function media()
    {
        return $this->hasMany('jorenvanhocht\Blogify\Models\media');
    }

    public function alias()
    {
        return $this->hasMany('jorenvanhocht\Blogify\Models\alias');
    }

    public function tag()
    {
        return $this->belongsToMany('jorenvanhocht\Blogify\Models\tag', 'posts_have_tags', 'post_id', 'tag_id');
    }

    public function status()
    {
        return $this->belongsTo('jorenvanhoch\Blogify\Models\Status');
    }

    public function visibility()
    {
        return $this->belongsTo('jorenvanhoch\Blogify\Models\Visibility');
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

    public function setPublishDateAttribute($value)
    {
        $this->attributes['publish_date'] = date("Y-m-d H:i:s", strtotime($value));
    }

    public function getPublishDateAttribute($value)
    {
        return date("d-m-Y H:i", strtotime($value));
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

    public function scopeForAdmin( $query )
    {
        return $query;
    }

    public function scopeForReviewer( $query )
    {
        return $query->whereReviewerId( Auth::user()->id );
    }

    public function scopeForAuthor( $query )
    {
        return $query->whereUserId( Auth::user()->id );
    }

    public function scopeBySlug( $query, $slug )
    {
        return $query->whereSlug($slug)->first();
    }

    public function scopeForPublic($query)
    {
        return $query->where('publish_date', '<=', date('Y-m-d H:i:s'))
                    ->where('visibility_id', '=', '1');
    }
}