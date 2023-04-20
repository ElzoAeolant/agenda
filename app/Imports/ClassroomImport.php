<?php

namespace App\Imports;

use App\Classroom;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClassroomImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ( $row['scholarperiod_id'] != '' && $row['shift'] != '' && $row['level'] != '' && $row['grade'] != '' ){
            $currentClassroom = Classroom::where([['scholarperiod_id',str_replace("\"","",$row['scholarperiod_id'])],['shift',str_replace("\"","",$row['shift'])],
                                            ['level',str_replace("\"","",$row['level'])],['grade',str_replace("\"","",$row['grade'])]])->first();
            if($currentClassroom==null){
                return new Classroom([
                    'shift' => $row['shift'],
                    'level' => $row['level'],
                    'grade' => $row['grade'], 
                    'section'=> $row['section'], 
                    'short_name' => $row['short_name'],
                    'region' => $row['region'], 
                ]);
            }
        }
    }
}