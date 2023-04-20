<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'scholarperiod_id','shift','level', 'grade', 'section','region','short_name','intranet_id'
    ];

    public function getDescriptorAttribute()
    {
       return "{$this->level}_{$this->grade}_{$this->section}";
    }

    /**
     * The users that belong to the classroom.
    */
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_has_classroom', 'user_id', 'classroom_id');
    }

}
