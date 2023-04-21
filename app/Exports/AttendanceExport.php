<?php

namespace App\Exports;

use App\Statement;
use App\User;
use App\User_Has_Classroom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    protected $classroomId;
    protected $dateFirst;
    protected $dateSecond;
    function __construct($cl_id,$d1,$d2){
        $this->classroomId = $cl_id;
        $this->dateFirst = $d1;
        $this->dateSecond = $d2;
    }
    public function headings(): array
    {
        return ["Motivo", "Alumno", "Creado", "Actualizado"];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {   
        $usersIds = User_Has_Classroom::Where('classroom_id',$this->classroomId)->get('user_id')->toArray();
        $usersNames = User::WhereIn('id',$usersIds)->get('name')->toArray();
        $statements = Statement::WhereIn('to',$usersNames)
                    ->WhereIn('statementtype_id',Array(10,11,12,13,14))
                    ->whereBetween('created_at',[date($this->dateFirst),date($this->dateSecond)])
                    ->orderBy('created_at', 'ASC')
                    ->get(['details','to','created_at','updated_at']);
        return $statements;
    }
}
