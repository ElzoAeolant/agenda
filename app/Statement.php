<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
//use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

use Carbon\Carbon;


class Statement extends Model
{
    use Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'details', 'user_id', 'statementtype_id','to','created_at','updated_at'
    ];

   //protected $appends = ['sign' => 0];

   public function getCreatedAtAttribute($date)
   {
       return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
   }

    public function getUpdatedAtAttribute($date)
    {
       return Carbon::createFromFormat('Y-m-d H:i:s', $date)->locale('es')->isoFormat('dddd, D MMMM YYYY')." (".Carbon::createFromFormat('Y-m-d H:i:s', $date)->diffforhumans().")";//format('Y-m-d');
    }

    public function getSignAttribute($value)
    {
        return $value;
    }

    /**
     * The users that belong to the statements.
    */
    public function users()
    {
        return $this->belongsToMany('App\User',  'user_has_statement', 'statement_id', 'user_id');
    }

}
