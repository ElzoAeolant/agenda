<?php

namespace App\Imports;

use App\Classroom;
use App\User;
use App\User_Has_Classroom;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class User_Has_Classroom_Import implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = User::where('username',str_replace("\"","",$row['username']))->first();
        $short_name = str_replace("\"","",$row['short_name']);
       
        $oClassroom = Classroom::where(['short_name'=>$short_name])->first();
        if($user != null and $oClassroom!=null){
            return new User_Has_Classroom([
                'user_id'=>$user->id,
                'classroom_id'=>$oClassroom->id,
                'is_tutor'=>str_replace("\"","",$row['is_tutor']),
            ]);
        }
    }
}
