<?php

namespace App\Imports;

use App\User_Has_User;
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class User_Has_User_Import implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        /**TODO: Importar con las relaciones de username, dni de apoderado y alumno. */
        $ppff = User::where('username',str_replace("\"","",$row['user_parent_username']))->first();
        $student = User::where('username',str_replace("\"","",$row['user_child_username']))->first();
        if($ppff != null and $student!=null){
            //if ($ppff->id==$row['user_parent_id'] and $student->id==$row['user_child_id'])
            {
                return new User_Has_User([
                    'user_parent_id' => $ppff->id,//$row['user_parent_id'],
                    'user_child_id' => $student->id,//$row['user_child_id'],
                    'scholarperiod_id' => $row['scholarperiod_id'],
                ]);
            }
        }else{
            dd($ppff,$student,$row);
        }
    }
}
