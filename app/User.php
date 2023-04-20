<?php
  
namespace App;
  
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
  
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','name', 'email', 'password', 'details'
    ];
  
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
  
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the actual classroom for the user.
     */
    public function classrooms()
    {
        return $this->belongsToMany('App\Classroom', 'user_has_classroom', 'user_id', 'classroom_id');
    }
    
    /**
     * Get the statements for the user.
     */
    public function statements()
    {
        return $this->belongsToMany('App\Statement', 'user_has_statement', 'user_id','statement_id')->withTimestamps()->withPivot('classroom_id','sign');
    }


}