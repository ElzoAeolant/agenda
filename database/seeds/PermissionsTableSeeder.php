<?php

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Permission list for users
        Permission::create(['name' => 'users.index']);
        Permission::create(['name' => 'users.edit']);
        Permission::create(['name' => 'users.show']);
        Permission::create(['name' => 'users.create']);
        Permission::create(['name' => 'users.destroy']);
        Permission::create(['name' => 'users.change']);

        //Permission list for roles
        Permission::create(['name' => 'roles.index']);
        Permission::create(['name' => 'roles.edit']);
        Permission::create(['name' => 'roles.show']);
        Permission::create(['name' => 'roles.create']);
        Permission::create(['name' => 'roles.destroy']);

        //Permission list for statements
        Permission::create(['name' => 'statements.index']);
        Permission::create(['name' => 'statements.edit']);
        Permission::create(['name' => 'statements.show']);
        Permission::create(['name' => 'statements.create']);
        Permission::create(['name' => 'statements.destroy']);
        Permission::create(['name' => 'statements.sign']);
        Permission::create(['name' => 'statements.print']);

        //Permission list for delays
        Permission::create(['name' => 'delays.index']);
        Permission::create(['name' => 'delays.edit']);
        Permission::create(['name' => 'delays.show']);
        Permission::create(['name' => 'delays.create']);
        Permission::create(['name' => 'delays.destroy']);
        Permission::create(['name' => 'delays.sign']);
        Permission::create(['name' => 'delays.print']);


        //Permission to import data from intranet.
        Permission::create(['name' => 'intranet.import']);

        //Permission to Attendance
        Permission::create(['name' => 'attendance.index']);
        Permission::create(['name' => 'attendance.edit']);
        Permission::create(['name' => 'attendance.show']);
        Permission::create(['name' => 'attendance.create']);
        Permission::create(['name' => 'attendance.send']);
        Permission::create(['name' => 'attendance.scan']);
        Permission::create(['name' => 'attendance.changehour']);



        //Permission to Family Schools
        Permission::create(['name' => 'familyschools.index']);
        Permission::create(['name' => 'familyschools.edit']);
        Permission::create(['name' => 'familyschools.show']);
        Permission::create(['name' => 'familyschools.create']);
        //Permission::create(['name' => 'attendance.destroy']);
        Permission::create(['name' => 'familyschools.send']);
        Permission::create(['name' => 'familyschools.scan']);
        Permission::create(['name' => 'familyschools.changehour']);

        // create role administrator 
        $admin = Role::create(['name'=>'Admin']);

        // create role technical administrator
        $tech_admin = Role::create(['name'=>'Tech Admin']);
        
        //Guest whithout permissions, only for welcome page.
        Role::create(['name'=>'Guest']);
 
        //PPFF Parent or tutor
        $ppff = Role::create(['name'=>'PPFF']);

        // Teacher tutor
        $teachertutor = Role::create(['name'=>'Profesor tutor']);

        // Teacher by hours
        $teacherhours = Role::create(['name'=>'Profesor por horas']);

        // Convivencia escolar
        $convivencia = Role::create(['name'=>'Convivencia escolar']);
        
        // Psicología
        $psicologia = Role::create(['name'=>'Psicología']);
        
        // Administrativo
        $administrativo = Role::create(['name'=>'Administrativo']);

        //Estudiante
        $student = Role::create(['name'=>'Estudiante']);

        //Capturar Asistencia
        $attendanceCapture = Role::create(['name'=>'Capturar Asistencia']);

        //Consultar Asistencia
        $attendaneceView = Role::create(['name'=>'Consultar Asistencia']);

        // give permission to roles

        $admin->givePermissionTo(Permission::all());
        $tech_admin->givePermissionTo(Permission::all());
        
        $attendanceCapture->givePermissionTo([
            'attendance.index',
            'attendance.create',
            'attendance.scan',
        ]);

        $attendaneceView->givePermissionTo([
            'attendance.index',
        ]);

        $convivencia->givePermissionTo([
            'delays.index',
            'delays.show',
            'delays.create',
            'delays.print',
            'statements.index',
            'statements.show',
            'statements.create',
            'statements.print',
            'attendance.index',
            'attendance.edit',
            'attendance.create',
            'attendance.send',
            'attendance.scan',
            'attendance.changehour',
            ]);

        $psicologia->givePermissionTo([
            'statements.index',
            'statements.show',
            'statements.create',
            'statements.print',
            ]);
        
        $administrativo->givePermissionTo([
            'statements.index',
            'statements.show',
            'statements.create',
            'statements.print',
            ]);

        $ppff->givePermissionTo([
            'users.change'
            ]);

        $student->givePermissionTo([
            'statements.index',
            'delays.index',
            ]);

        $teachertutor->givePermissionTo([
            'statements.index',
            'statements.show',
            'statements.edit',
            'statements.destroy',
            'statements.create',
            'statements.sign',
            'attendance.index'
            ]);

        $teacherhours->givePermissionTo([
            'statements.index',
            'statements.show',
            'statements.create',
            'attendance.index',
        ]);

        // Assign roles to the users
        
        $user = User::find(1); //Super Admin
        $user->assignRole('Tech Admin');

        $user = User::find(2); //Content Admin
        $user->assignRole('Admin');

        $user = User::find(3); //Teacher
        $user->assignRole('Profesor tutor');
        

        $user = User::find(4); //Convivencia
        $user->assignRole('Convivencia escolar');

        $user = User::find(5); //Psicologia
        $user->assignRole('Psicología');

        $user = User::find(6); //Administrativo
        $user->assignRole('Administrativo');
        
        $user = User::find(7); //PPFF
        $user->assignRole('PPFF');

        $user = User::find(8); //Estudiante
        $user->assignRole('Estudiante');

        $user = User::find(9); //Estudiante
        $user->assignRole('Estudiante');
    }
}
