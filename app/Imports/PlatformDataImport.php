<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PlatformDataImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'CLASSROOMS' => new ClassroomImport(),
            'TEACHERS' => new UsersImport(),
            'STUDENTS' => new UsersImport(),
            'PPFFs' => new UsersImport(),
            'USER_HAS_USER'=> new User_Has_User_Import(),
            'USER_HAS_CLASSROOM' => new User_Has_Classroom_Import()
        ];
    }
}
