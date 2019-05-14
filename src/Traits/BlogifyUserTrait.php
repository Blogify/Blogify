<?php

namespace jorenvanhocht\Blogify\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use jorenvanhocht\Blogify\Models\Role;

Trait BlogifyUserTrait
{

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

    public function scopeByHash($query, $hash)
    {
        return $query->whereHash($hash)->first();
    }

    public function scopeNewUsersSince($query, $date)
    {
        return $query->where('created_at', '>=', $date)->get();
    }

    public function scopeByRole($query, $role_id)
    {
        return $query->whereRoleId($role_id);
    }

    public function scopeReviewers($query)
    {
        $reviewer_role_id = Role::whereName('Reviewer')->first()->id;
        $admin_role_id = Role::whereName('Admin')->first()->id;

        return $query->where(function($q) use ($reviewer_role_id, $admin_role_id)
        {
            $q->whereRoleId($reviewer_role_id)
                ->orWhere('role_id', '=', $admin_role_id);
        })->where('id', '<>', Auth::user()->id)->get();
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
        return $this->attributes['nombre'].' '.$this->attributes['apellidos'];
    }
}

