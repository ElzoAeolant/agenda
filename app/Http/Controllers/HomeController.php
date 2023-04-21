<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\IntranetStudent;
use App\IntranetClassroom;
use App\Classroom;
use App\IntranetEquivalences;
use App\IntranetEquivalencesStaff;
use App\IntranetStaff;
use App\User;
use App\Statement;
use App\StatementType;
use DB;
use Hash;
use PDF;

use Illuminate\Support\Str;
use Auth;
use Attendance;
use Carbon\Carbon;

use App\Exports\UsersExport;
use App\Exports\AttendanceExport;

use App\Imports\PlatformDataImport;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $statementforuser = DB::table('user_has_statement')
            ->where('user_id', auth()->user()->id)
            ->pluck('statement_id');

        $statements = Statement::latest()
            ->whereIn('id', $statementforuser)
            ->get();


        $typesStatement = StatementType::where('type','STATEMENTS')->get();

        $data = array();
        $withoutSign = 0;
        $delaysWithoutSign = 0;
        foreach ($statements as $statement) {
            //change the display for user_id//
            $statement->user_id = User::find($statement->user_id)->name;
            $sign = DB::table('user_has_statement')
                ->where(['user_id' => auth()->user()->id, 'statement_id' => $statement->id])
                ->pluck('sign')->first();
            if ($sign) {
                $statement->status = 'yellow';
            } else {
                $statementtype_valid = $typesStatement->filter(function($item) use($statement) {
                    return $item->id == $statement->statementtype_id;
                })->first();
                if(!$statementtype_valid){
                    $delaysWithoutSign++;    
                }else{
                    ++$withoutSign;
                }
            }
        }

        //Children for a PPFF
        $children = DB::table('user_has_user')
            ->where(['user_parent_id' => auth()->user()->id, 'scholarperiod_id' => 1])
            ->get();
        $childrenList = array();
        foreach ($children as $key => $child) {
            if (!isset($childrenList[$child->user_child_id])) {
                $childrenList[$child->user_child_id] = User::find($child->user_child_id)->name;
            }
        }


        $statementss = Statement::latest()
            ->where('user_id', auth()->user()->id)->get();

        $statementsSend = 0;
        $delaysSend = 0;
        $statementsSendCount = 0;
        $delaysSendCount = 0;
        foreach ($statementss as $statement) {
            

            $statementtype_valid = $typesStatement->filter(function($item) use($statement) {
                return $item->id == $statement->statementtype_id;
            })->first();
            if(!$statementtype_valid){
                $delaysSend++;
            }else{
                $statementsSend++;
            }

            $signs = DB::table('user_has_statement')
                ->where(['statement_id' => $statement->id])
                ->pluck('sign');
            $countsign = 0;
            foreach ($signs as $sign) {
                if ($sign) {
                    ++$countsign;
                }
            }
            if ($countsign == sizeof($signs)) {
                if(!$statementtype_valid){
                     $delaysSendCount = 0;
                }else{
                    $statementsSendCount++;
                }                               
            }
        }

        $data['statements.withoutSign'] = $withoutSign;
        $data['statements_send'] = $statementsSendCount . '/' . $statementsSend;
        $data['delays.withoutSign'] = $delaysWithoutSign;
        $data['delays'] = $delaysSendCount . '/' . $delaysSend;
        $types = StatementType::all();

        return view('dashboard', compact('statements', 'types', 'data', 'childrenList'));
    }

    public function ajaxRequest(Request $request)
    {

        if ($request->has('method')) {
            switch ($request->method) {
                case 'getstudents':
                    if ($request->has('param')) {
                        $selected_classroom = $request->param;
                        $usersdb = DB::table('user_has_classroom')
                            ->where('classroom_id', $selected_classroom)
                            ->where(['scholarperiod_id' => 1]) //TODO: Cambiar por la configuración en BD
                            ->pluck('user_id');
                        $members = User::whereIn('id', $usersdb)
                            ->orderBy('name', 'desc')
                            ->get(['id', 'name']);
                        $students = array();
                        foreach ($members as $member) {
                            if (User::find($member->id)->hasRole('Estudiante')) {
                                array_push($students, $member);
                            }
                        }
                    }
                    return response()->json(['success' => 'Got Simple Ajax Request.', 'data' => json_encode($students)]);
                    break;
                case 'getstudentsattendance':
                    return response()->json(app('App\Http\Controllers\AttendanceController')->getStudentsAttendance($request));
                    break;
                case 'getstudentsDelays':
                    return response()->json(app('App\Http\Controllers\AttendanceController')->getRegister($request));
                    break;
                case 'storeattendance':
                    return response()->json(app('App\Http\Controllers\AttendanceController')->store($request));
                    break;
                case 'updateattendance':
                    return response()->json(app('App\Http\Controllers\AttendanceController')->update($request));
                    break;
            }
        }
        return response()->json(['success' => 'fail', 'data' => '']);
    }

    public function importDataFromIntranet()
    {
        $details = '<p style="text-align: center;">&nbsp;&nbsp;<img src="https://jebp.edu.pe/web/images/jebp_logo.png" alt="LOGO" width="400" height="100" /></p>' .
            '<table style="border-collapse: collapse; width: 98.2079%; height: 336px;" border="1">' .
            '<tbody>' .
            '<tr style="height: 48px;">' .
            '<td style="width: 806px; text-align: center; height: 48px;" colspan="2"><span style="font-weight: bold; font-size: 18pt;"><span style="font-family: \'book antiqua\', palatino, serif;">DATOS PERSONALES</span></span></td>' .
            '</tr>' .
            '<tr style="height: 48px;">' .
            '<td style="width: 403px; height: 48px;">Contacto 1</td>' .
            '<td style="width: 403px; height: 48px;">' .
            '<p>Nombre</p>' .
            '<p>Tel&eacute;fono</p>' .
            '</td>' .
            '</tr>' .
            '<tr style="height: 48px;">' .
            '<td style="width: 403px; height: 48px;">Contacto 2</td>' .
            '<td style="width: 403px; height: 48px;">' .
            '<p>Nombre</p>' .
            '<p>Tel&eacute;fono</p>' .
            '</td>' .
            '</tr>' .
            '</tbody>' .
            '</table>' .
            '<p style="font-size: medium;">&nbsp;</p>';
        $classrooms = IntranetClassroom::where([
            ['year', '=', Date('Y') - 1],
            ['deleted', '=', 0],
        ])
            ->orderBy('descriptor', 'desc')
            ->get(['id', 'descriptor', 'tutor']);

        $staff = IntranetStaff::get(['id', 'name', 'user', 'password']);

        $error = false;

        foreach ($classrooms as $classrrom) {
            /**
             * Crear al usuario tutor, su salón y sus alumnos.
             */
            $intranettutor = $staff->find($classrrom->tutor);

            if ($intranettutor) {
                if (User::where('email', $intranettutor->user . "@agenda.jebp.com")->exists()) {
                    echo "<p>Salón :" . $classrrom->descriptor . " no fue creado porque el usuario tutor " . $intranettutor->user . "@agenda.jebp.com" . " existe.</p>";
                    $error = true;
                    continue;
                }
                $infotutor['name'] = $intranettutor->name;
                $infotutor['username'] = $intranettutor->user;
                $infotutor['password'] = Hash::make($intranettutor->user);
                $infotutor['email'] = $intranettutor->user . "@agenda.jebp.com";
                $infotutor['email_verified_at'] = now();
                $infotutor['creted_at'] = now();
                $infotutor['updated_at'] = now();
                $infotutor['details'] = $details;
                $tutor = User::create($infotutor);
                $tutor->assignRole('Profesor tutor');

                $descriptor = explode("_", $classrrom->descriptor);
                $agendaClassroom['scholarperiod_id'] = 1; // Cambiar por lo que está en la base de datos config
                $agendaClassroom['shift'] = $descriptor[0];
                $agendaClassroom['level'] = $descriptor[1];
                $agendaClassroom['grade'] = $descriptor[2];
                $agendaClassroom['section'] = $descriptor[3];
                $agendaClassroom['region'] = 'CENTRO';
                $agendaClassroom['intranet_id'] = $classrrom->id;
                $agendaClassroom['user_id'] = $tutor->id;

                $classroomnew = Classroom::create($agendaClassroom);


                DB::table('user_has_classroom')->insert([
                    'user_id' => $tutor->id,
                    'classroom_id' => $classroomnew->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $students = IntranetStudent::where([
                    ['Classroom_id', '=', $classrrom->id],
                ])
                    ->orderBy('nombre', 'desc')
                    ->get(['id', 'codigo_siagie as dni', 'nombre as name', 'apellido_paterno as ap', 'apellido_materno as am']);

                foreach ($students as $student) {
                    if (User::where('email', $student->dni . "@agenda.jebp.com")->exists()) {
                        $error = true;
                        continue;
                    }
                    $input['name'] = strtoupper($student->name . " " . $student->ap . " " . $student->am);
                    if ($student->dni == '') {
                        $student->dni = rand(10000000, 99999999);
                    }
                    $input['email'] = $student->dni . "@agenda.jebp.com";
                    $input['username'] = $student->dni;
                    $input['password'] = Hash::make($student->dni);
                    $input['email_verified_at'] = now();
                    $input['creted_at'] = now();
                    $input['updated_at'] = now();
                    $input['details'] = $details;

                    $user = User::create($input);
                    $user->assignRole('Estudiante');
                    DB::table('user_has_classroom')->insert([
                        'user_id' => $user->id,
                        'classroom_id' => $classroomnew->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            } else {
            }
        }

        /**
         * Crear las relaciones de los demás profesores con los cursos
         * TODO
         */

        /*$ClassroomsStaff = IntranetEquivalencesStaff::where([
            ['year','=', Date('Y')-1],
            ])
        ->groupBy('Classroom_id')
        ->get('Classroom_id');
            */
        //print_r($ClassroomsStaff);

        // foreach ($ClassroomsStaff as $ClassroomStaff) {
        //     DB::table('user_has_classroom')->insert([
        //         'user_id' => $tutor->id,
        //         'classroom_id' => $ClassroomStaff->id,
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ]); 
        // }

        if ($error) {
            return "Importación con errores...";
        } else {
            return "Importación satisfactoria..";
        }
    }

    /**
     * Change the current user test
     */
    public function changeuser($id)
    {
        if (isset($id)) {
            $parent = session()->get('parent');
            $parentId = auth()->user()->id;
            $parentName = auth()->user()->name;
            Auth::logout();
            session()->flush();
            $user = User::find($id);
            Auth::login($user);
            if ($parent == "") {
                session(['parent' => $parentName]);
                session(['parentId' => $parentId]);
                session(['users.change' => 'inherited']);
                session(['statements.show' => 'inherited']);
                session(['statements.create' => 'inherited']);
                session(['statements.print' => 'inherited']);
                session(['statements.sign' => 'inherited']);
                session(['delays.sign' => 'inherited']);
            }
            if ($parent != "" and $parent != auth()->user()->name) {
                session(['users.change' => 'inherited']);
                session(['statements.show' => 'inherited']);
                session(['statements.create' => 'inherited']);
                session(['statements.print' => 'inherited']);
                session(['statements.sign' => 'inherited']);
                session(['delays.sign' => 'inherited']);
            }
            return redirect()->route('home');
        }
    }

    public function downloadPDF()
    {
        //$qrs = array($qr1,$qr2,$qr3,$qr4,$qr5,$qr6);
        $users = User::whereHas(
		'roles', function($q){
			$q->where('name','Estudiante');
		}
	)->get();
	//dd(sizeof($users));
        
        if(empty($users) or sizeof($users)<5){
            dd($users,$qrs);
        }
        $pdf = PDF::loadView('pdf', compact('users'));
        return view('pdf', compact('users'));
        //return $pdf->download('qrs.pdf');
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function importExportView()
    {
        return view('import');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function exportAttendance()
    {
        return Excel::download(new AttendanceExport(52), 'attendance.xlsx');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function import()
    {

        Excel::import(new PlatformDataImport, request()->file('file'));

        return back()->withStatus(__('IMPORT DATA successfully updated.'));
    }
}
