<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntranetEquivalencesStaff extends Model
{
    protected $connection = 'mysqlIntranet';
    protected $table = 'EquivalencesStaff';
}
