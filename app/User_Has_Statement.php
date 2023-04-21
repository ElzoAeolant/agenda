<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_Has_Statement extends Model
{
    //
    protected $table = 'user_has_statement';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','classroom_id', 'statement_id', 'sign'
    ];
}
