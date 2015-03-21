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
        return $this->hasMany('jorenvanhocht\Blogify\Models\user');
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

    public function scopeByAdminRoles( $query )
    {
        $query->whereName('admin')
            ->orWhere('name', '=', 'Author')
            ->orWhere('name', '=', 'reviewer');
    }
}