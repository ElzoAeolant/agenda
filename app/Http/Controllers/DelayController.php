<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use App\Statement;
use App\Classroom;
use App\StatementType;
use App\User;
use App\User_Has_Classroom;
use DB;
use Carbon\Carbon;

class DelayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $activetab = 'delays_inbox';
        $statements = array();
        $types = StatementType::where('type','ATTENDANCE')->get();
            
        if($request->has('delays_send')){

            $statements = Statement::latest()
            ->where('user_id',auth()->user()->id)
            ->whereIn('statementtype_id', $types->pluck('id'))
            ->paginate(10);
            
            foreach ($statements as $statement) {

                $signs = DB::table('user_has_statement')
                ->where(['statement_id'=>$statement->id])
                ->pluck('sign');
                $countsign = 0;
                $status = 'white';
                foreach ($signs as $sign) {
                    if ($sign){
                        ++$countsign;
                    }
                }
                if($countsign>0){
                    $status = "orange";
                    if($countsign == sizeof($signs)){
                        $status = "yellow";
                    }
                }
                
                $statement->status = $status;

                //change the display for user_id//
                $statement->user_name = User::find($statement->user_id)->name;


                $statement->details = "<div class='myclass mceNonEditable'>".
                                        '<br>Emitido el '.$statement->updated_at.'</br><br> para: '.$statement->to .'</br><br>de '. $statement->user_name.'</br>'.
                                        $statement->details
                                        ."</div>";
                $statement->color = $types->filter(function($item) use($statement) {
                    return $item->id == $statement->statementtype_id;
                })->first()->color;
            }

            $statements->withPath(route('delays.index','delays_send'));
            $activetab = 'delays_send';
        }else {

            // Inbox
            $statementforuser = DB::table('user_has_statement')
            ->where('user_id',auth()->user()->id)
            ->pluck('statement_id');

            if($request->has('selected')){
                $statementReference = Statement::find($request['selected']);
               
                $day = Carbon::parse($statementReference->created_at)->format('d');
                $month = Carbon::parse($statementReference->created_at)->format('m');

                $statements = Statement::latest()
                ->whereIn('id',$statementforuser)
                ->whereDay('created_at', $day)
                ->whereMonth('created_at',  $month)
                ->whereIn('statementtype_id', $types->pluck('id'))
                ->paginate(10);
            }else{
                $statements = Statement::latest()
                ->whereIn('id',$statementforuser)
                ->whereIn('statementtype_id', $types->pluck('id'))
                ->paginate(10);
            }

            foreach ($statements as $statement) {
                //change the display for user_id//
                $statement->user_name = User::find($statement->user_id)->name;
                $sign = DB::table('user_has_statement')
                ->where(['user_id'=>auth()->user()->id,'statement_id'=>$statement->id])
                ->pluck('sign')->first();
                if ($sign){
                    $statement->status = 'yellow';
                }
                $statement->details = "<div class='myclass mceNonEditable'>".
                                        '<br>Recibido el '.$statement->updated_at.'</br><br> para: '.$statement->to .'</br><br>de '. $statement->user_name.'</br>'.
                                        $statement->details
                                        ."</div>";
                $statement->color = $types->filter(function($item) use($statement) {
                    return $item->id == $statement->statementtype_id;
                })->first()->color;
            }
            
            $statements->withPath(route('delays.index','delays_inbox'));
            
        }
        
        return view('delays.index',compact('statements','activetab','types'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->hasAnyRole(['Profesor tutor', 'Profesor por horas']) and !auth()->user()->hasAnyRole(['Convivencia escolar','Capturar Asistencia'])) {
            $usersdb = DB::table('user_has_classroom')
                    ->where('user_id',auth()->user()->id)
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
        }else{
            $classrooms = Classroom::where([
                ['scholarperiod_id','=', 1],
                ['shift','<>','TEST']
                ])
            ->orderBy('level', 'asc')
            ->get();
        }
        
        $types = StatementType::all()->where('id','>',1)
                                ->where('type','STATEMENTS'); //Se excluye mensaje a tutor y asistencia

        return view('delays.create',compact('classrooms','types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       if(auth()->user()->hasAnyRole(['Estudiante'])){
            $this->validate($request, [
                'details' => 'required'
            ]);
            $request['user_id'] = auth()->user()->id;
            $request['classroom'] = User_Has_Classroom::where('user_id',auth()->user()->id)
                                    ->pluck('classroom_id')[0]; // Se asume que el alumno está registrado únicamente en un salón
            
            //Buscar a los tuores del aula.
            $tutores = User_Has_Classroom::where(['classroom_id'=>$request['classroom'],'is_tutor'=>1])->get();
            if(sizeof($tutores)>1){
                $request['to'] = 'Tutores';
            }else{
                $request['to'] = User::find($tutores[0]->user_id)->name;
            }

            $request['statementtype_id'] = 1; //No modificar, siempre será mensaje a tutor.
            $statement = Statement::create($request->all());

            foreach ($tutores as $tutor) {
                DB::table('user_has_statement')->insert([
                    'statement_id' => $statement->id,
                    'classroom_id' => $request['classroom'],
                    'user_id' => $tutor->user_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
       }else{
            $this->validate($request, [
                'statementtype_id' => 'required',
                'details' => 'required',
                'classroom' => 'required',
                'student_id' => 'required_without:all|array|min:1'
            ]);

            $request['user_id'] = auth()->user()->id;

            if($request['all']=='on'){
                $request['to'] = 'Todos';
                
                $usersdb = DB::table('user_has_classroom')
                ->where('classroom_id',$request['classroom'])
                ->where(['scholarperiod_id'=>1]) //TODO: Cambiar por la configuración en BD
                ->pluck('user_id');

                $members = User::whereIn('id',$usersdb)
                ->get('id');
                
                if(sizeof($members)==0){
                    return redirect()->back()->withInput()->withErrors(['student_id'=>'Faltan alumnos']);   
                }

                $statement = Statement::create($request->all());
                foreach ($members as $member) {
                    if(User::find($member->id)->hasRole('Estudiante')){
                        DB::table('user_has_statement')->insert([
                            'statement_id' => $statement->id,
                            'classroom_id' => $request['classroom'],
                            'user_id' => $member->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }else{
                if ( sizeof($request['student_id']) == 1 ){
                    $student = strtoupper(User::find($request['student_id'][0])->name);
                    $request['to'] = $student;
                    $statement = Statement::create($request->all());
                    DB::table('user_has_statement')->insert([
                        'statement_id' => $statement->id,
                        'classroom_id' => $request['classroom'],
                        'user_id' => $request['student_id'][0],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }else{
                    $request['to'] = 'Alumnos';
                    $statement = Statement::create($request->all());
                    foreach ($request['student_id'] as $student) {
                        DB::table('user_has_statement')->insert([
                            'statement_id' => $statement->id,
                            'classroom_id' => $request['classroom'],
                            'user_id' => $student,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }
        }
        return redirect()->route('delays.index',['delays_send'])
                        ->withStatus('success-'.trans('Statement created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Statement  $statement
     * @return \Illuminate\Http\Response
     */
    public function show(Statement $statement)
    {
        //
        $showinfo = array();
        $autorizedusers = DB::table('user_has_statement')->where('statement_id',$statement->id)->get();
        if ($statement->user_id ==  auth()->user()->id/*OWNER*/ && $autorizedusers ) {
            $owner = User::find($statement->user_id);
            $showinfo['owner'] = $owner->name;
            $showinfo['id'] = $statement->id;
            $showinfo['created_at'] = $statement->updated_at;
            $classroom = Classroom::find($autorizedusers->first()->classroom_id);
            $showinfo['classroom'] = $classroom->descriptor;
            $type = StatementType::find($statement->statementtype_id);
            $showinfo['color'] = $type->color;
            $showinfo['details'] = $statement->details;
            $showinfo['type'] = $type->name;
            $showinfo['students'] = array();
            $students = User::whereIn('id',$autorizedusers->pluck('user_id'))
                                    ->orderBy('name', 'desc')
                                    ->get(['id','username','name']);
            if(sizeof($students) != sizeof($autorizedusers)){
                dd($students,$autorizedusers);
            }
            foreach ($students as $key => $value) {
                $student = array();
                $student['name'] = $value->name;
                $student['username'] = $value->username;
                foreach ($autorizedusers as $au) {
                    if($au->user_id == $value->id){
                        if($au->sign==0){
                            $student['signed_at']= '-------';
                        }else{
                            $student['signed_at']= Carbon::createFromFormat('Y-m-d H:i:s', $au->updated_at)->format('d-m-Y H:i')." | ". Carbon::createFromFormat('Y-m-d H:i:s', $au->updated_at)->diffforhumans();
                        }
                    }
                }
                array_push ( $showinfo['students'], $student);
            }
            return view('delays.show',compact('showinfo','autorizedusers'));

        }else if($autorizedusers->has(auth()->user()->id) && $from != 'delays_inbox'){

        }else{
            throw UnauthorizedException::forPermissions(['none']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Statement  $statement
     * @return \Illuminate\Http\Response
     */
    public function edit(Statement $statement)
    {
        //
        $showinfo = array();
        $autorizedusers = DB::table('user_has_statement')->where('statement_id',$statement->id)->get();
        if ($statement->user_id ==  auth()->user()->id/*OWNER*/ && $autorizedusers ) {
            $owner = User::find($statement->user_id);
            $showinfo['owner'] = $owner->name;
            $showinfo['id'] = $statement->id;
            $showinfo['created_at'] = $statement->updated_at;
            $classroom = Classroom::find($autorizedusers->first()->classroom_id);
            $showinfo['classroom'] = $classroom->descriptor;
            $type = StatementType::find($statement->statementtype_id);
            $showinfo['color'] = $type->color;
            $showinfo['details'] = $statement->details;
            $showinfo['type'] = $type->name;
            $showinfo['students'] = array();
            $students = User::whereIn('id',$autorizedusers->pluck('user_id'))
                                    ->orderBy('name', 'desc')
                                    ->get(['id','username','name']);
            if(sizeof($students) != sizeof($autorizedusers)){
                dd($students,$autorizedusers);
            }
            foreach ($students as $key => $value) {
                $student = array();
                $student['name'] = $value->name;
                $student['username'] = $value->username;
                foreach ($autorizedusers as $au) {
                    if($au->user_id == $value->id){
                        if($au->sign==0){
                            $student['signed_at']= '-------';
                        }else{
                            $student['signed_at']= Carbon::createFromFormat('Y-m-d H:i:s', $au->updated_at)->format('d-m-Y H:i')." | ". Carbon::createFromFormat('Y-m-d H:i:s', $au->updated_at)->diffforhumans();
                        }
                    }
                }
                array_push ( $showinfo['students'], $student);
            }
            return view('delays.edit',compact('showinfo','statement','autorizedusers'));

        }else if($autorizedusers->has(auth()->user()->id)){

        }else{
            throw UnauthorizedException::forPermissions(['none']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Statement  $statement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Statement $statement)
    {
        $this->validate($request, [
            'details' => 'required',
        ]);

        //$statement = Statement::find($statement);
        $statement->details = $request['details'];
        $statement->save();
        
        return redirect()->route('delays.index',['delays_send'])
         ->withStatus('success-'.trans('Actualización realizada con éxito.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Statement  $statement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Statement $statement)
    {
        return redirect()->back()
         ->withStatus('warning-'.trans('Esta operación no ha sido habilitada.'));
    }

    /**
     * Sign every statement in the inbox user
     *
     * @return \Illuminate\Http\Response
     */
    public function sign()
    {
        $statementforuser = DB::table('user_has_statement')
         ->where(['user_id'=>auth()->user()->id])
         ->get();

        $typesStatement = StatementType::where('type','STATEMENTS')->get();
        $delayStatement = array();
        
        foreach ($statementforuser as $statementfor) {
            $statement = Statement::find($statementfor->statement_id);
            
            $statementtype_valid = $typesStatement->filter(function($item) use($statement) {
                return $item->id == $statement->statementtype_id;
            })->first();

            if(!$statementtype_valid){
                array_push($delayStatement,$statement->id);
            }
        }
        
         $statementforuser = DB::table('user_has_statement')
         ->where(['user_id'=>auth()->user()->id])
         ->wherein('statement_id',collect($delayStatement))
         ->update(['sign' => 1,'updated_at'=>now()]);
         
        return redirect()->back()
         ->withStatus('success-'.trans('Todas las tardanzas fueron marcados de enterado.'));
    }
    public function justifyAttendance()
    {
        $statementforuser = DB::table('user_has_statement')
         ->where(['user_id'=>auth()->user()->id])
         ->get();

        $typesStatement = StatementType::where('type','STATEMENTS')->get();
        $delayStatement = array();
        
        foreach ($statementforuser as $statementfor) {
            $statement = Statement::find($statementfor->statement_id);
            
            $statementtype_valid = $typesStatement->filter(function($item) use($statement) {
                return $item->id == $statement->statementtype_id;
            })->first();

            if(!$statementtype_valid){
                array_push($delayStatement,$statement->id);
            }
        }
        
         $statementforuser = DB::table('user_has_statement')
         ->where(['user_id'=>auth()->user()->id])
         ->wherein('statement_id',collect($delayStatement))
         ->update(['sign' => 1,'updated_at'=>now()]);
         
        return redirect()->back()
         ->withStatus('success-'.trans('Todas las tardanzas fueron marcados de enterado.'));
    }
}
