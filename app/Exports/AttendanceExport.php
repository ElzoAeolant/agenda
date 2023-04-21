<?php

namespace App\Exports;

use App\Statement;
use App\User;
use App\User_Has_Classroom;
use App\User_Has_Statement;
use Maatwebsite\Excel\Concerns\FromCollection;

class AttendanceExport implements FromCollection
{
    protected $classroomId;
    function __construct($cl_id){
        $this->classroomId = $cl_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {   
        $usersIds = User_Has_Classroom::Where('classroom_id',$this->classroomId)->get('user_id')->toArray();
        $usersNames = User::WhereIn('id',$usersIds)->get('name')->toArray();
        $statements = Statement::WhereIn('to',$usersNames)->WhereIn('statementtype_id',Array(10,11,12,13,14))->get('id')->toArray();
        dd($statements,User_Has_Statement::WhereIn('statement_id',$statements)->get());
        return Statement::where();
    }
}
