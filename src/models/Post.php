<?php namespace jorenvanhocht\Blogify\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model{

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

    /**
     * Relationship with the User model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('jorenvanhocht\Blogify\Models\user');
    }

    /**
     * Relationship with the Comment model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comment()
    {
        return $this->hasMany('jorenvanhocht\Blogify\Models\comment');
    }

    /**
     * Relationship with the Category model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('jorenvanhocht\Blogify\Models\category');
    }

    /**
     * Relationship with the Media model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function media()
    {
        return $this->hasMany('jorenvanhocht\Blogify\Models\media');
    }

    /**
     * Relationship with the Alias model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function alias()
    {
        return $this->hasMany('jorenvanhocht\Blogify\Models\alias');
    }

    /**
     * Relationship with the Tag model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tag()
    {
        return $this->belongsToMany('jorenvanhocht\Blogify\Models\tag', 'posts_have_tags', 'post_id', 'tag_id');
    }

}