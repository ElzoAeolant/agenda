<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntranetClassroom extends Model
{
    protected $connection = 'mysqlIntranet';
    protected $table = 'Classroom';
}
