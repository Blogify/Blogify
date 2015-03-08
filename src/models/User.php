<?php namespace jorenvanhocht\Blogify\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable, CanResetPassword, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table        = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable     = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden       = ['password', 'remember_token'];

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
     * Relationship with the Role model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('jorenvanhocht\Blogify\Models\role');
    }

    /**
     * Relationship with the History model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany('jorenvanhocht\Blogify\Models\history');
    }

    /**
     * Relationship with the Post model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function post()
    {
        return $this->hasMany('jorenvanhocht\Blogify\Models\post');
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

}
