<?php namespace jorenvanhocht\Blogify\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model{

    use SoftDeletes;

    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table        = 'media';

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo('jorenvanhocht\Blogify\Models\post');
    }


}