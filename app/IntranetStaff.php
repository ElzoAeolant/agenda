<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntranetStaff extends Model
{
    protected $connection = 'mysqlIntranet';
    protected $table = 'Staff';
}
