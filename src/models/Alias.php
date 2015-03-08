<?php namespace jorenvanhocht\Blogify\Models;

use Illuminate\Database\Eloquent\Model;

class Alias extends Model{

    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table        = 'aliases';

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
    public $timestamps      = false;

    ///////////////////////////////////////////////////////////////////////////
    // Relationships
    ///////////////////////////////////////////////////////////////////////////

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