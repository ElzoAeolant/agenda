<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_Has_Classroom extends Model
{
    //
    protected $table = 'user_has_classroom';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','classroom_id', 'is_tutor'
    ];
}
