<?php namespace jorenvanhocht\Blogify\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;

Trait BlogifyUserTrait {

    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    | For more information pleas check out the official Laravel docs at
    | http://laravel.com/docs/5.0/eloquent#relationships
    |
    */

    public function role()
    {
        return $this->belongsTo('jorenvanhocht\Blogify\Models\role');
    }

    public function history()
    {
        return $this->hasMany('jorenvanhocht\Blogify\Models\history');
    }

    public function post()
    {
        return $this->hasMany('jorenvanhocht\Blogify\Models\post');
    }

    public function comment()
    {
        return $this->hasMany('jorenvanhocht\Blogify\Models\comment');
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

    public function scopeByHash( $query, $hash )
    {
        return $query->whereHash($hash)->first();
    }

    public function scopeNewUsersSince( $query, $date )
    {
        return $query->where('created_at', '>=', $date)->get();
    }

    public function scopeByRole( $query, $role_id )
    {
        return $query->whereRoleId( $role_id );
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    |
    | For more information pleas check out the official Laravel docs at
    | http://laravel.com/docs/master/eloquent#accessors-and-mutators
    |
    */

    public function getFullNameAttribute()
    {
        return $this->attributes['firstname'] . ' ' . $this->attributes['name'];
    }
}

