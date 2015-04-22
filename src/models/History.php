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
        return $this->belongsTo('App\user');
    }


}