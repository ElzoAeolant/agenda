<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Exports\AttendanceExport;
use App\Classroom;
use App\User;
use App\Statement;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Response;
use DB;
use Carbon\Carbon;
use URL;

class AttendanceController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:attendances.create', ['only' => [/*'register',*/'store']]);
        $this->middleware('permission:attendance.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:attendance.delete', ['only' => ['destroy']]);
        $this->middleware('permission:attendance.show', ['only' => ['show']]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        if (auth()->user()->hasAnyRole(['Profesor tutor', 'Profesor por horas','Capturar Asistencia Auxiliares']) and !auth()->user()->hasAnyRole(['Convivencia escolar','Capturar Asistencia'])) {
            $usersdb = DB::table('user_has_classroom')
                ->where('user_id', auth()->user()->id)
                ->pluck('classroom_id');

            if(auth()->user()->id == 6){
                $classrooms = Classroom::whereIn('id',$usersdb)
                ->where([
                    ['scholarperiod_id','=', 1],
                    //['shift','<>','TEST']
                    ])
                ->orderBy('level', 'asc')
                ->get();    
            }else{
                $classrooms = Classroom::whereIn('id',$usersdb)
                ->where([
                    ['scholarperiod_id','=', 1],
                    ['shift','<>','TEST']
                    ])
                ->orderBy('level', 'asc')
                ->get();
            }
        } else {
            $classrooms = Classroom::where([
                ['scholarperiod_id', '=', 1],
                ['shift','<>','TEST']
            ])
                ->orderBy('level', 'asc')
                ->get();
        }

        return view('attendance.register', compact('classrooms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Setup the validator
        $rules = array('register' => 'required', 'registerfor' => 'required|in:checkin,checkout', 'type' => 'required|in:Manual,Scan');
        $msg = '';

        $time = now();
        $replaceTime = false;
        if ($request->user()->can('attendance.changehour') && isset($request['isselectedtime'])) {
            if ($request['isselectedtime'] == "true") {
                $rules['selectedtime'] = 'required';
                $time->hour = explode(':', $request['selectedtime'])[0];
                $time->minute = explode(':', $request['selectedtime'])[1];
                $replaceTime = true;
            }
        }

        $validator = Validator::make($request->all(), $rules);

        // Validate the input and return correct response
        if ($validator->fails()) {
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toJson(JSON_PRETTY_PRINT)

            );
        }


        if ($request['type'] == 'Scan') {
            $user_db = User::where('username', $request['register'])->first();
            if ($user_db != null) {
                $user_id = $user_db->id;
            } else {
                return array(
                    'success' => false,
                    'errors' => json_encode(array(1 => '[' . now()->format('d/M/Y g:ia') . '] El usuario no existe en la BD.'))
                );
            }
        } elseif ($request['type'] == 'Manual') {
            $user_id = $request['register'];
        } else {
            return array(
                'success' => false,
                'errors' => json_encode(array(1 => '[' . now()->format('d/M/Y g:ia') . '] La solicitud no se reconoce.'))
            );
        }

        if ($user_id == null) {
            return array(
                'success' => false,
                'errors' => json_encode(array(1 => '[' . now()->format('d/M/Y g:ia') . '] El usuario no existe en la BD.'))
            );
        }
        $user_db = User::find($user_id);
        $userClassroom = $user_db->classrooms->toArray();

        if (sizeof($userClassroom) > 1) {
            return array(
                'success' => false,
                'errors' => json_encode(array(1 => '[' . now()->format('d/M/Y g:ia') . '] El usuario está relacionado con más de un salón.'))
            );
        }
        $userClassroom = $userClassroom[0];
        $attendancetypes = DB::table('attendance_types')
            ->where(['scholarperiod_id' => 1]) //TODO: Cambiar por la configuración en BD
            ->where('level', $userClassroom['level'])
            ->get();

        //Verificar sino existe registro anterior para el alumno.
        $hasRegister = DB::table('user_has_attendance')
            ->where(['user_id' => $user_id])
            ->where(function ($query) {
                $query->whereBetween('checkin_at', [now()->startOfDay(), now()->endOfDay()])
                    ->orWhereBetween('checkout_at', [now()->startOfDay(), now()->endOfDay()]);
            })
            ->first();

        //Identificar el tipo de asistencia que le corresponde. Entrada normal, Tardanza, etc. 
        $today = $time;
        $todayMin = now();
        $todayMax = now();
        $attendancetype_user = null;
        //Se asumen que no existen traslapes entre los horarios, por eso en cuánto encuentra un resultado termina la búsqueda.
        $hashAttendanceTypes = array();
        $i = 0;
        foreach ($attendancetypes as $attendancetype) {
            $hashAttendanceTypes[$attendancetype->id] = $i;
            ++$i;
            $todayMin->hour = explode(':', $attendancetype->min_hour)[0];
            $todayMin->minute = explode(':', $attendancetype->min_hour)[1];
            $todayMax->hour = explode(':', $attendancetype->max_hour)[0];
            $todayMax->minute = explode(':', $attendancetype->max_hour)[1];
            if ($today >= $todayMin and $today <= $todayMax and $request['registerfor'] == $attendancetype->type) {
                $attendancetype_user = $attendancetype;
                break;
            } else {
                continue;
            }
        }
        //Si no se encuentra un rango válido para asociar la asistencia se emite el error.
        if ($attendancetype_user == null) {
            return array(
                'success' => false,
                'errors' => json_encode(array(1 => '[' . now()->format('d/M/Y g:ia') . '] El horario está fuera de los rangos definidos para la ' . ($request['registerfor'] == 'checkin' ? 'entrada.' : 'salida.')))
            );
        }

        if ($hasRegister != null) {
            if ($request['registerfor'] == 'checkin') {
                //Existe registro previo para la entrada y no se reemplaza?
                if (!is_null($hasRegister->checkin_at) and $replaceTime == false) {
                    //Se registra el intento de captura y se marca para comprobar cambios.
                    DB::table('user_has_attendance')
                        ->where(['id' => $hasRegister->id])
                        ->update(['updated_at' => now()]);
                    $attendancetype_user = $attendancetypes->get($hashAttendanceTypes[$hasRegister->attendancetype_checkin_id]);
                    $msg = "Entrada registrada con antelación el día: ";
                } else {
                    //(No existe registro previo) || (se reemplaza)
                    DB::table('user_has_attendance')
                        ->where(['id' => $hasRegister->id])
                        ->update(['checkin_at' => $today, 'attendancetype_checkin_id' => $attendancetype_user->id, 'updated_at' => now()]);
                    $hasRegister->checkin_at = $today;
                    $msg = "Se " . ($replaceTime ? 'cambió' : 'registró') . " correctamente la entrada el día : ";
                }
                $today = Carbon::createFromFormat('Y-m-d H:i:s', $hasRegister->checkin_at);
            } elseif ($request['registerfor'] == 'checkout') {
                //Existe registro previo para la salida y no se reemplaza?
                if (!is_null($hasRegister->checkout_at) and $replaceTime == false) {
                    //Se registra el intento de captura y se marca para comprobar cambios.
                    DB::table('user_has_attendance')
                        ->where(['id' => $hasRegister->id])
                        ->update(['updated_at' => now()]);
                    $attendancetype_user = $attendancetypes->get($hashAttendanceTypes[$hasRegister->attendancetype_checkin_id]);
                    $msg = "Salida registrada con antelación el día: ";
                } else {
                    //(No existe registro previo) || (se reemplaza)
                    DB::table('user_has_attendance')
                        ->where(['id' => $hasRegister->id])
                        ->update(['checkout_at' => $today, 'attendancetype_checkout_id' => $attendancetype_user->id, 'updated_at' => now()]);
                    $hasRegister->checkout_at = $today;
                    $msg = "Se " . ($replaceTime ? 'cambió' : 'registró') . " correctamente la salida el día : ";
                }
                $today = Carbon::createFromFormat('Y-m-d H:i:s', $hasRegister->checkout_at);
            }
            return array('success' => true, 'msg' => $msg, 'id' => $user_id, 'for' => User::find($user_id)->name, 'time' => $today->format('d/M/Y g:ia'), 'color' => $attendancetype_user->color);
        } else {
            //No existe evidencia previa se crea el registro con la entrada o la salida. 
            $checkin = null;
            $checkout = null;
            $checkinAttendanceType = null;
            $checkoutAttendanceType = null;
            if ($request['registerfor'] == 'checkin') {
                $msg = "Se registró correctamente la entrada el día : ";
                $checkin = $today;
                $checkinAttendanceType = $attendancetype_user->id;
            } elseif ($request['registerfor'] == 'checkout') {
                $msg = "Se registró correctamente la salida el día : ";
                $checkout = $today;
                $checkoutAttendanceType = $attendancetype_user->id;
            }
            DB::table('user_has_attendance')->insert([
                'user_id' => $user_id,
                'registered_by' => auth()->user()->id,
                'attendancetype_checkin_id' => $checkinAttendanceType,
                'attendancetype_checkout_id' => $checkoutAttendanceType,
                'checkin_at' => $checkin,
                'checkout_at' => $checkout,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            return array('success' => true, 'msg' => $msg, 'id' => $user_id, 'for' => User::find($user_id)->name, 'time' => $today->format('d/M/Y g:ia'), 'color' => $attendancetype_user->color);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = array('register' => 'required', 'registerfor' => 'required|in:checkin,checkout');

        $validator = Validator::make($request->all(), $rules);

        // Validate the input and return correct response
        if ($validator->fails()) {
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toJson(JSON_PRETTY_PRINT)

            );
        }

        $attendance_id = $request['register'];

        //Verificar sino existe registro anterior para el alumno.
        $hasRegister = DB::table('user_has_attendance')
            ->where(['id' => $attendance_id])->first();

        //Incorporar validación en caso de que no exista el registro.

        $affected = 0;
        $is_justified_in = 0;
        if ($request['registerfor'] == 'checkin') {
            if (!$hasRegister->is_justified_checkin) {
                $is_justified = 1;
            } else {
                $is_justified = 0;
            }
            $is_justified_in = $is_justified;
            $affected = DB::table('user_has_attendance')
                ->where(['id' => $hasRegister->id])
                ->update(['is_justified_checkin' => $is_justified, 'updated_at' => now()]);

            $attendancetype = DB::table('attendance_types')
                ->where('id', $hasRegister->attendancetype_checkin_id)
                ->first();

        } else if ($request['registerfor'] == 'checkout') {
            if (!$hasRegister->is_justified_checkout) {
                $is_justified = 1;
            } else {
                $is_justified = 0;
            }

            $affected = DB::table('user_has_attendance')
                ->where(['id' => $hasRegister->id])
                ->update(['is_justified_checkout' => $is_justified, 'updated_at' => now()]);

            $attendancetype = DB::table('attendance_types')
                ->where('id', $hasRegister->attendancetype_checkout_id)
                ->first();
        }

        $hasRegister = DB::table('user_has_attendance')
            ->where(['id' => $attendance_id])->first();

        //Actualizar en caso de que exista el comunicado, la justificación
        if($hasRegister->statement_id!=null){
            $attendanceStatement = Statement::find($hasRegister->statement_id);
            //Tardanzas
            if($attendanceStatement->statementtype_id==10 or $attendanceStatement->statementtype_id==11){
                $attendanceStatement->statementtype_id = $is_justified_in?11:10;
                $attendanceStatement->save();
            }
            //Inasistencias
            if($attendanceStatement->statementtype_id==12 or $attendanceStatement->statementtype_id==13){
                $attendanceStatement->statementtype_id = $is_justified_in?13:12;
                $attendanceStatement->save();
            }
        }


        if ($affected == 1) {
            return array('success' => true, 'msg' => "La asistencia se actualizó satisfactoriamente.", 'id' => $hasRegister->id, 'for' => User::find($hasRegister->user_id)->name, 'time' => now()->format('d/M/Y g:ia'), 'color' => $attendancetype->color, 'is_justified' => $is_justified);
        } else {
            return array(
                'success' => false,
                'errors' => json_encode(array(1 => '[' . now()->format('d/M/Y g:ia') . '] No fue posible actualizar la asistencia de : ' . ($request['registerfor'] == 'checkin' ? 'entrada.' : 'salida.')))
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Statement  $statement
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        return redirect()->route('attendance.index')
            ->withStatus('warning-' . trans('Esta operación no ha sido habilitada.'));
    }

    /**
     * Send statements about unattendance.
     *
     */
    public function send()
    {
        if (auth()->user()->hasAnyRole(['Profesor tutor', 'Profesor por horas','Capturar Asistencia Auxiliares']) and !auth()->user()->hasAnyRole(['Convivencia escolar','Capturar Asistencia'])) {
            $usersdb = DB::table('user_has_classroom')
                ->where('user_id', auth()->user()->id)
                ->pluck('classroom_id');

           if(auth()->user()->id == 6){
                $classrooms = Classroom::whereIn('id',$usersdb)
                ->where([
                    ['scholarperiod_id','=', 1],
                    //['shift','<>','TEST']
                    ])
                ->orderBy('level', 'asc')
                ->get();    
            }else{
                $classrooms = Classroom::whereIn('id',$usersdb)
                ->where([
                    ['scholarperiod_id','=', 1],
                    ['shift','<>','TEST']
                    ])
                ->orderBy('level', 'asc')
                ->get();
            }
        } else {
            $classrooms = Classroom::where([
                ['scholarperiod_id', '=', 1],
                ['shift','<>','TEST']
            ])
                ->orderBy('level', 'asc')
                ->get();
        }

        return view('attendance.send', compact('classrooms'));
    }

    /**
     * Send statements about delays.
     *
     */
    public function delays()
    {

       if (auth()->user()->hasAnyRole(['Profesor tutor', 'Profesor por horas','Capturar Asistencia Auxiliares']) and !auth()->user()->hasAnyRole(['Convivencia escolar','Capturar Asistencia'])) {
            $usersdb = DB::table('user_has_classroom')
                ->where('user_id', auth()->user()->id)
                ->pluck('classroom_id');

            if(auth()->user()->id == 6){
                $classrooms = Classroom::whereIn('id',$usersdb)
                ->where([
                    ['scholarperiod_id','=', 1],
                    //['shift','<>','TEST']
                    ])
                ->orderBy('level', 'asc')
                ->get();    
            }else{
                $classrooms = Classroom::whereIn('id',$usersdb)
                ->where([
                    ['scholarperiod_id','=', 1],
                    ['shift','<>','TEST']
                    ])
                ->orderBy('level', 'asc')
                ->get();
            }
        } else {
            $classrooms = Classroom::where([
                ['scholarperiod_id', '=', 1],
                ['shift','<>','TEST']
            ])
                ->orderBy('level', 'asc')
                ->get();
        }

        return view('attendance.delays', compact('classrooms'));
    }
    /**
     * Show webcam qr scanner.
     *
     */
    public function showscan()
    {
        return view('attendance.showscan');
    }
    /**
     * Store scaned attendance
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getQRCode(Request $request)
    {
        if (auth()->user()->username == 'admin.tech') {
            $users = User::orderBy('id', 'DESC')->paginate(20);
            return view('attendance.myqrcode', compact('users'))
                ->with('i', ($request->input('page', 1) - 1) * 20);
        } else {
            $users = User::where('username', auth()->user()->username)->paginate(10);
            return view('attendance.myqrcode', compact('users'))->with('i', ($request->input('page', 1) - 1) * 10);
        }
        // if(!file_exists(public_path('images/qrcodes/'.$username.'.png'))){
        //     \QrCode::size(2000)
        //     ->format('png')
        //     ->generate($username, public_path('images/qrcodes/'.$username.'.png'));
        // }
        // $filename = URL::to('/').'/images/qrcodes/'.$username.'.png';
    }

    public function getStudentsAttendance(Request $request)
    {
        if ($request->has('param')) {

            $selected_classroom = $request->param;
            $usersdb = DB::table('user_has_classroom')
                ->where('classroom_id', $selected_classroom)
                ->where(['scholarperiod_id' => 1]) //TODO: Cambiar por la configuración en BD
                ->pluck('user_id');

            $members = User::whereIn('id', $usersdb)
                ->orderBy('name', 'desc')
                ->get(['id', 'name']);

            $attendancetypes = DB::table('attendance_types')
                ->where(['scholarperiod_id' => 1]) //TODO: Cambiar por la configuración en BD
                ->where('level', Classroom::find($selected_classroom)->level)
                ->get();

            //Se asumen que no existen traslapes entre los horarios, se genera un hash para acceder al identificador del tipo.
            $hashAttendanceTypes = array();
            $i = 0;
            foreach ($attendancetypes as $attendancetype) {
                $hashAttendanceTypes[$attendancetype->id] = $i;
                ++$i;
            }

            $students = array();
            foreach ($members as $member) {
                if (User::find($member->id)->hasRole('Estudiante')) {
                    array_push($students, $member);
                }
            }
            $members = $students;
            foreach ($members as $member) {
                $hasRegister = DB::table('user_has_attendance')
                    ->where(['user_id' => $member->id])
                    ->where(function ($query) {
                        $query->whereBetween('checkin_at', [now()->startOfDay(), now()->endOfDay()])
                            ->orWhereBetween('checkout_at', [now()->startOfDay(), now()->endOfDay()]);
                    })
                    ->first();
                if ($hasRegister != null) {
                    if ($hasRegister->checkin_at != null) {
                        $member['attendance_in'] =  Carbon::createFromFormat('Y-m-d H:i:s', $hasRegister->checkin_at)->format('d/M/Y g:ia');
                        $atttype = $attendancetypes->get($hashAttendanceTypes[$hasRegister->attendancetype_checkin_id]);
                        if ($atttype != null) {
                            $member['checkin_color'] = $atttype->color;
                        }
                    } else {
                        $member['attendance_in'] = '';
                    }

                    if ($hasRegister->checkout_at != null) {
                        $member['attendance_out'] =  Carbon::createFromFormat('Y-m-d H:i:s', $hasRegister->checkout_at)->format('d/M/Y g:ia');
                        $atttype = $attendancetypes->get($hashAttendanceTypes[$hasRegister->attendancetype_checkout_id]);
                        if ($atttype != null) {
                            $member['checkout_color'] = $atttype->color;
                        }
                    } else {
                        $member['attendance_out'] = '';
                    }
                } else {
                    $member['attendance_in'] = '';
                    $member['attendance_out'] = '';
                }
            }
        }
        return ['success' => 'Got Simple Ajax Request.', 'data' => json_encode($members)];
    }

    public function getRegister(Request $request)
    {
        if ($request->has('param') and $request->has('date')) {
            $today = $request->date;
            $selected_classroom = $request->param;
            $usersdb = DB::table('user_has_classroom')
                ->where('classroom_id', $selected_classroom)
                ->where(['scholarperiod_id' => 1]) //TODO: Cambiar por la configuración en BD
                ->pluck('user_id');

            $members = User::whereIn('id', $usersdb)
                ->orderBy('name', 'desc')
                ->get(['id', 'name']);


            $attendancetypes = DB::table('attendance_types')
                ->where(['scholarperiod_id' => 1]) //TODO: Cambiar por la configuración en BD
                ->where('level', Classroom::find($selected_classroom)->level)
                ->get();

            //Se asumen que no existen traslapes entre los horarios, se genera un hash para acceder al identificador del tipo.
            $hashAttendanceTypes = array();
            $i = 0;
            foreach ($attendancetypes as $attendancetype) {
                $hashAttendanceTypes[$attendancetype->id] = $i;
                ++$i;
            }

            $students = array();
            foreach ($members as $member) {
                if (User::find($member->id)->hasRole('Estudiante')) {
                    array_push($students, $member);
                }
            }

            $members = $students;
            $students = array();
            foreach ($members as $member) {
                $hasRegister = DB::table('user_has_attendance')
                    ->where(['user_id' => $member->id])
                    ->where(function ($query) use ($today) {
                        $init = Carbon::parse($today)->startOfDay();
                        $end = Carbon::parse($today)->endOfDay();
                        $query->whereBetween('checkin_at', [$init, $end])
                            ->orWhereBetween('checkout_at', [$init, $end]);
                    })
                    ->first();
                $foundDelay = false;
                if ($hasRegister != null) {
                    $member['attendance_id'] = $hasRegister->id;
                    $member['attendance_notified'] = 1;
                    if ($hasRegister->checkin_at != null) {
                        $member['attendance_in_justified'] = $hasRegister->is_justified_checkin;
                        $member['attendance_in'] =  Carbon::createFromFormat('Y-m-d H:i:s', $hasRegister->checkin_at)->format('d/M/Y g:ia');
                        $atttype = $attendancetypes->get($hashAttendanceTypes[$hasRegister->attendancetype_checkin_id]);
                        $member['checkin_color'] = $atttype->color;
                        if ($atttype != null && $atttype->require_justification) {
                            $member['in_require_justification'] = true;
                            $foundDelay = true;
                            $member['attendance_notified'] = $hasRegister->statement_id!=null?1:0;
                        }
                    } else {
                        $member['attendance_in'] = '';
                    }

                    if ($hasRegister->checkout_at != null) {
                        $member['attendance_out_justified'] = $hasRegister->is_justified_checkout;
                        $member['attendance_out'] =  Carbon::createFromFormat('Y-m-d H:i:s', $hasRegister->checkout_at)->format('d/M/Y g:ia');
                        $atttype = $attendancetypes->get($hashAttendanceTypes[$hasRegister->attendancetype_checkout_id]);
                        $member['checkout_color'] = $atttype->color;
                        if ($atttype != null && $atttype->require_justification) {
                            $member['out_require_justification'] = true;
                            $foundDelay = true;
                        }
                    } else {
                        $member['attendance_out'] = '';
                    }

                    if ($foundDelay) {
                        array_push($students, $member);
                    }
                }else{
                    //Aquí se revisan las inasistencias
                    $queryDate = Carbon::parse($today)->endOfDay();
                    $hasRegister = DB::table('user_has_attendance')
                        ->where(['user_id' => $member->id])
                        ->where(['created_at' => $queryDate])
                        ->first();
                    if($hasRegister != null){
                        $member['attendance_id'] = $hasRegister->id;
                        $member['attendance_notified'] = 1;
                        $member['checkin_color'] = 'danger';
                        $member['checkout_color'] = 'danger';
                        $member['in_require_justification'] = true;
                        $member['attendance_in_justified'] = $hasRegister->is_justified_checkin;
                        $member['attendance_notified'] = $hasRegister->statement_id!=null?1:0;
                        $member['attendance_out'] = 'Inasistencia';
                        $member['attendance_in'] = 'Inasistencia';
                    }else{
                        $member['attendance_out'] = '';
                        $member['attendance_in'] = '';
                    }
                }
            }
            //$members = $students;
            return ['success' => true, 'data' => json_encode($members)];
        }
        return array(
            'success' => false,
            'errors' => json_encode(array(1 => '[' . now()->format('d/M/Y g:ia') . '] Parámetros inválidos'))
        );
    }
    public function emit(Request $request)
    {
        $this->validate($request, [
            'classroom' => 'required',
            //'details' => 'required',
            'date' => 'required'
        ]);

        $today = $request->date;

        $usersdb = DB::table('user_has_classroom')
            ->where('classroom_id', $request['classroom'])
            ->where(['scholarperiod_id' => 1]) //TODO: Cambiar por la configuración en BD
            ->pluck('user_id');

        $members = User::whereIn('id', $usersdb)
            ->get('id');

        if (sizeof($members) == 0) {
            return redirect()->back()->withInput()->withErrors(['classroom' => 'El salón seleccionado no cuenta con alumnos']);
        }

        $request['user_id'] = auth()->user()->id;  // Es quien escribe el comunicado.

        $tardanza = 10;
        $inasistencia = 12;


        $attendancetypes = DB::table('attendance_types')
            ->where(['scholarperiod_id' => 1]) //TODO: Cambiar por la configuración en BD
            ->where('level', Classroom::find( $request['classroom'])->level)
            ->get();

        //Se asumen que no existen traslapes entre los horarios, se genera un hash para acceder al identificador del tipo.
        $hashAttendanceTypes = array();
        $i = 0;
        foreach ($attendancetypes as $attendancetype) {
            $hashAttendanceTypes[$attendancetype->id] = $i;
            ++$i;
        }


        foreach ($members as $member) {
            $destination = User::find($member->id);
            if ($destination->hasRole('Estudiante')) {
                //Verificar si al usuario no se le ha notificado.
                $init = Carbon::parse($today)->startOfDay();
                $end = Carbon::parse($today)->endOfDay();
                $statementsSent = DB::table('user_has_statement')
                    ->where('user_id', $destination->id)
                    ->whereBetween('created_at', [$init, $end])
                    ->get();
                    
                $hasbeenotified = false;
                foreach ($statementsSent as $statement) {
                    //Verificar que el comunicado sea de tardanza
                    $statementReceived = Statement::where('id',$statement->statement_id)->first();
                    if ($statementReceived->statementtype_id >= 10 and $statementReceived->statementtype_id <= 13) {
                        //Ya se le notificó.
                        $hasbeenotified = true;
                        break;
                    }
                }

                //Sino se ha notificado se buscan sus registros de tardanza o inasistencia
                $hasRegister = DB::table('user_has_attendance')
                    ->where(['user_id' => $member->id])
                    ->where(function ($query) use ($today) {
                        $init = Carbon::parse($today)->startOfDay();
                        $end = Carbon::parse($today)->endOfDay();
                        $query->whereBetween('checkin_at', [$init, $end])
                            ->orWhereBetween('checkout_at', [$init, $end]);
                    })
                    ->first();
                
                $foundDelay = false;
                if ($hasRegister != null) {
                    
                    if ($hasRegister->checkin_at != null) {
                        $atttype = $attendancetypes->get($hashAttendanceTypes[$hasRegister->attendancetype_checkin_id]);
                        if ($atttype != null && $atttype->require_justification) {
                            $foundDelay = true;
                        }
                    }

                    // if ($hasRegister->checkout_at != null) {
                    //     $atttype = $attendancetypes->get($hashAttendanceTypes[$hasRegister->attendancetype_checkin_id]);
                    //     if ($atttype != null && $atttype->require_justification) {
                    //         $foundDelay = true;
                    //     }
                    // }

                    if ($foundDelay) {
                        if ($hasbeenotified and $hasRegister->statement_id != null) {
                            continue;//No se envia nada al usuario.
                        }else{
                            $request['to'] = $destination->name;
                            $request['statementtype_id'] = $tardanza+$hasRegister->is_justified_checkin;
                            $request['created_at'] = Carbon::parse($today)->startOfDay();
                            $request['updated_at'] = Carbon::parse($today)->startOfDay();
                            $request['details'] = '<p>El estudiante llegó tarde.</p>';
                            
                            $statement = Statement::create($request->all());
                            
                            DB::table('user_has_attendance')
                            ->where(['id' => $hasRegister->id])
                            ->update(['statement_id' => $statement->id, 'updated_at' => Carbon::parse($today)->startOfDay()]);
                            
                            DB::table('user_has_statement')->insert([
                                'statement_id' => $statement->id,
                                'classroom_id' => $request['classroom'],
                                'user_id' => $member->id,
                                'created_at' => Carbon::parse($today)->startOfDay(),
                                'updated_at' => Carbon::parse($today)->startOfDay()
                            ]);
                        }
                    }
                }else{
                    //Se notifican las inasistencias del día. se tiene que verificar que el día que se consulta ya culminó 
                    // o en su defecto se alcanzó la hora de salida del día actual.
                    $queryDate = Carbon::parse($today)->endOfDay();
                    $now = now();
                    $outDate = now()->hour(11)->minute(50);
                    if($now > $queryDate or $now > $outDate){
                        //Se pueden enviar inasistencias.
                        //Paso 1- Verificar sino se ha creado un registro de inasistencia
                        //Sino se ha notificado se buscan sus registros de tardanza o inasistencia
                        $hasRegister = DB::table('user_has_attendance')
                        ->where(['user_id' => $member->id])
                        ->where(function ($query) use ($today) {
                            $init = Carbon::parse($today)->startOfDay();
                            $end = Carbon::parse($today)->endOfDay();
                            $query->whereBetween('checkin_at', [$init, $end])
                                ->orWhereBetween('checkout_at', [$init, $end]);
                        })
                        ->first();

                        //Paso 1-1 Se crea el registro de inasistencia sino existe registro previo
                        if($hasRegister == null){
                            DB::table('user_has_attendance')->insert([
                                'user_id' => $member->id,
                                'registered_by' => auth()->user()->id,
                                'attendancetype_checkin_id' => 1, //Se registra la inasistencia
                                'attendancetype_checkout_id' => 1, //Se registra la inasistencia
                                'checkin_at' => null,
                                'checkout_at' => null,
                                'created_at' => $queryDate,
                                'updated_at' => $queryDate
                            ]);
                        }

                        $hasRegister = DB::table('user_has_attendance')
                        ->where(['user_id' => $member->id])
                        ->where(['created_at' => $queryDate])
                        ->first();

                        //Paso 2- Se Verifica que no se ha enviado el comunicado asociado.
                        $init = Carbon::parse($today)->startOfDay();
                        $end = Carbon::parse($today)->endOfDay();
                        $statementsSent = DB::table('user_has_statement')
                            ->where('user_id', $destination->id)
                            ->whereBetween('created_at', [$init, $end])
                            ->get();
                            
                        $hasbeenotified = false;
                        foreach ($statementsSent as $statement) {
                            //Verificar que el comunicado sea de tardanza
                            $statementReceived = Statement::where('id',$statement->statement_id)->first();
                            if (($statementReceived->statementtype_id >= 10 and $statementReceived->statementtype_id <= 13)/*Tardanzas*/ or $statementReceived->statementtype_id == 1/*Inasistencia*/) {
                                //Ya se le notificó.
                                $hasbeenotified = true;
                                break;
                            }
                        }

                        if ($hasbeenotified and $hasRegister->statement_id != null) {
                            continue;//No se envia nada al usuario.
                        }else{
                            $request['to'] = $destination->name;
                            $request['statementtype_id'] = $inasistencia;
                            $request['created_at'] = Carbon::parse($today)->startOfDay();
                            $request['updated_at'] = Carbon::parse($today)->startOfDay();
                            $request['details'] = '<p>El estudiante no asistió.</p>';
                            
                            $statement = Statement::create($request->all());
                            
                            DB::table('user_has_attendance')
                            ->where(['id' => $hasRegister->id])
                            ->update(['statement_id' => $statement->id, 'updated_at' => Carbon::parse($today)->startOfDay()]);

                            DB::table('user_has_statement')->insert([
                                'statement_id' => $statement->id,
                                'classroom_id' => $request['classroom'],
                                'user_id' => $member->id,
                                'created_at' => Carbon::parse($today)->startOfDay(),
                                'updated_at' => Carbon::parse($today)->startOfDay()
                            ]);
                        }
                    }
                }
            }
        }
        return redirect()->back()
                        ->withStatus('success-'.trans('Los comunicados se enviaron correctamente.'));
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function download(Request $request)
    {
        $this->validate($request, [
            'classroom_id' => 'required',
            'd1' => 'required',
            'd2' => 'required'
        ]);
    
        $input = $request->all();
        return Excel::download(new AttendanceExport($input['cl_id'],$input['d1'].' 00:00:00',$input['d2'].' 23:59:59'), 'attendance.xlsx');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        if (auth()->user()->hasAnyRole(['Profesor tutor', 'Profesor por horas','Capturar Asistencia Auxiliares']) and !auth()->user()->hasAnyRole(['Convivencia escolar','Capturar Asistencia'])) {
            $usersdb = DB::table('user_has_classroom')
                ->where('user_id', auth()->user()->id)
                ->pluck('classroom_id');

            if(auth()->user()->id == 6){
                $classrooms = Classroom::whereIn('id',$usersdb)
                ->where([
                    ['scholarperiod_id','=', 1],
                    //['shift','<>','TEST']
                    ])
                ->orderBy('level', 'asc')
                ->get();    
            }else{
                $classrooms = Classroom::whereIn('id',$usersdb)
                ->where([
                    ['scholarperiod_id','=', 1],
                    ['shift','<>','TEST']
                    ])
                ->orderBy('level', 'asc')
                ->get();
            }
        } else {
            $classrooms = Classroom::where([
                ['scholarperiod_id', '=', 1],
                ['shift','<>','TEST']
            ])
                ->orderBy('level', 'asc')
                ->get();
        }

        return view('attendance.export', compact('classrooms'));
    }

}
