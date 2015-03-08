<?php namespace jorenvanhocht\Blogify\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model{

    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table        = 'history';

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

    ///////////////////////////////////////////////////////////////////////////
    // Relationships
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Relationship with the User model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('jorenvanhocht\Blogify\Models\user');
    }


}