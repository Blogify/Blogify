<?php namespace jorenvanhocht\Blogify\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model{

    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table        = 'roles';

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
     * Relationship with the User model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->hasMany('jorenvanhocht\Blogify\Models\user');
    }


}