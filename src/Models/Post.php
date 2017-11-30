<?php

namespace jorenvanhocht\Blogify\Models;

use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Sunra\PhpSimple\HtmlDomParser;

class Post extends BaseModel
{

    use SoftDeletes;

    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table = 'blogify_posts';

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
        return $this->belongsTo('App\User', 'user_id')->withTrashed();
    }

    public function reviewer()
    {
        return $this->belongsTo('App\User', 'reviewer_id')->withTrashed();
    }

    public function comment()
    {
        return $this->hasMany('jorenvanhocht\Blogify\Models\Comment', 'post_id', 'id');
    }

    public function media()
    {
        return $this->hasMany('jorenvanhocht\Blogify\Models\Media', 'id');
    }

    public function tag()
    {
        return $this->belongsToMany('jorenvanhocht\Blogify\Models\tag', 'blogify_posts_have_tags', 'post_id', 'tag_id')->withTrashed();
    }

    public function status()
    {
        return $this->belongsTo('jorenvanhocht\Blogify\Models\Status');
    }

    public function visibility()
    {
        return $this->belongsTo('jorenvanhocht\Blogify\Models\Visibility');
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

    public function getCarbonPublishDateAttribute($value)
    {
        return new Carbon($this->publish_date);
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

    public function scopeForAdmin($query)
    {
        return $query;
    }

    public function scopeForReviewer($query)
    {
        return $query->whereReviewerId(Auth::user()->id);
    }

    public function scopeForAuthor($query)
    {
        return $query->whereUserId(Auth::user()->id);
    }

    public function scopeBySlug($query, $slug)
    {
        return $query->whereSlug($slug)->firstOrFail();
    }

    public function scopeForPublic($query)
    {
        return $query->where('publish_date', '<=', date('Y-m-d H:i:s'))
                    ->where('visibility_id', '=', '1');
    }

    public function scopeNotPress($query)
    {
            return $query->whereHas('tag', function($query){
                $query->where('name', '!=', 'Press');
            });
    }

    public function scopeBlogTags($query,$tag_id)
    {
            return $query->whereHas('tag', function($query) use ($tag_id) { 
                $query->where('tag_id', $tag_id); 
            });   
    }

    //Accessors

    /**
     * Get the post's image.
     *
     * @param  string  $value
     * @return string
     */
    public function getSrcImageAttribute($value)
    {
        if (is_null($this->image))
        {
            $dom = HtmlDomParser::str_get_html($this->content);
            $src = isset($dom->find('img')[0]) ? $dom->find('img')[0]->src : null ;

            return $src;

        }
        else
            return $this->image;

    }

    public function getAltImageAttribute($value)
    {
        if (is_null($this->image))
        {
            $dom = HtmlDomParser::str_get_html($this->content);
            $src = isset($dom->find('img')[0]) ? $dom->find('img')[0]->alt : null ;

            return $src;

        }
        else
            return $this->image;

    }
}