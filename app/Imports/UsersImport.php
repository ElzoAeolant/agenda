<?php
   
namespace App\Imports;
   
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
    
class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ( $row['username'] != '' ){
            $currentUser = User::where('username',str_replace("\"","",$row['username']))->first();
            if($currentUser==null){
                $user = new User([
                    'name'     => $row['name'],
                    'username'     => $row['username'],
                    'email'    => $row['username']."@jebp.smarteduperu.com", 
                    'password' => \Hash::make($row['password']),
                ]);
                $roles = explode('|',$row['role']);
                /*if(sizeof($roles)>1){
                    $roles = implode(',',$roles);
                }*/
                $user->assignRole($roles);
                return $user;
            }
        }
    }
}
