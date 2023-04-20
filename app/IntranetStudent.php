<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntranetStudent extends Model
{
    protected $connection = 'mysqlIntranet';
    protected $table = 'Student';
}
