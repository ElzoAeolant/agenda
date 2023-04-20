<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_Has_User extends Model
{
    //
    protected $table = 'user_has_user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_parent_id','user_child_id', 'scholarperiod_id'
    ];
}
