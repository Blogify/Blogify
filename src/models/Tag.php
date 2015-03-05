<?php namespace jorenvanhocht\Blogify\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model{

    use SoftDeletes;

    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table        = 'tags';

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
     * Relationship with the Post model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function post()
    {
        return $this->belongsToMany('jorenvanhocht\Blogify\Models\post', 'posts_have_tags', 'post_id', 'tag_id');
    }


}