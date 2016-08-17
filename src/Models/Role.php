<?php

namespace jorenvanhocht\Blogify\Models;

class Role extends BaseModel
{

    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table = 'blogify_roles';

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
    public $timestamps = false;

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
        return $this->hasMany('App\user');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    |
    | For more information pleas check out the official Laravel docs at
    | http://laravel.com/docs/5.0/eloquent#queryscopes
    |
    */

    public function scopeByAdminRoles($query)
    {
        $query->whereName('admin')
            ->orWhere('name', '=', 'Author')
            ->orWhere('name', '=', 'reviewer');
    }
}