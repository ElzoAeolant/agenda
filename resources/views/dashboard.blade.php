@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Dashboard')])
@push('head_scripts')
<link href='{!! asset('assets/fullcalendar-4.3.1/packages/core/main.css') !!}' rel='stylesheet' />
<link href='{!! asset('assets/fullcalendar-4.3.1/packages/daygrid/main.css') !!}' rel='stylesheet' />
<link href='{!! asset('assets/fullcalendar-4.3.1/packages/timegrid/main.css') !!}' rel='stylesheet' />
<link href='{!! asset('assets/fullcalendar-4.3.1/packages/bootstrap/main.css') !!}' rel='stylesheet' />
<link href='{!! asset('assets/fullcalendar-4.3.1/packages/list/main.css') !!}' rel='stylesheet' />

<script src='{!! asset('assets/fullcalendar-4.3.1/packages/core/main.js') !!}'></script>
<script src='{!! asset('assets/fullcalendar-4.3.1/packages/core/locales-all.js') !!}'></script>
<script src='{!! asset('assets/fullcalendar-4.3.1/packages/interaction/main.js') !!}'></script>
<script src='{!! asset('assets/fullcalendar-4.3.1/packages/daygrid/main.js') !!}'></script>
<script src='{!! asset('assets/fullcalendar-4.3.1/packages/timegrid/main.js') !!}'></script>
<script src='{!! asset('assets/fullcalendar-4.3.1/packages/list/main.js') !!}'></script>
<script src='{!! asset('assets/fullcalendar-4.3.1/packages/bootstrap/main.js') !!}'></script>
@endpush
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      @can('statements.index')
      <div class="col-lg-4 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
              <div class="card-icon">
                <i class="material-icons">content_copy</i>
              </div>
              <p class="card-category">{{ __('Statements') }}</p>
              <a href="{{ route('statements.index','statements.inbox') }}">
              <h3 class="card-title">Recibidos
                <small>({{$data['statements.withoutSign']}})</small>
              </h3>
              </a>
              @can('statements.show')
              <a href="{{ route('statements.index','statements_send') }}">
              <h3 class="card-title">
                Emitidos
                <small>({{$data['statements_send']}})</small>
              </h3>
              </a>
              @endcan
            </div>
            <div class="card-footer">
              <div class="stats">
                <h4>
                @can('statements.create')
                <i class="material-icons text-warning">library_add</i>
                <a href="{{ route('statements.create') }}">Ingresar</a>
                @else
                <i class="material-icons text-warning"></i>
                <a></a>
                @endcan
                </h4>
              </div>
            </div>
          </div>
        </div>
        @endcan
        @can('users.change')
        @if(sizeof($childrenList)>0)
        <div class="col-lg-4 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
              <div class="card-icon">
                <i class="material-icons">face</i>
              </div>
              <p class="card-category">{{ __('Estudiantes') }}</p>
              @foreach($childrenList as $id => $child)
              <a href="{{ route('users.change',$id) }}">
              <h5 class="card-title">{{$child}}
                <small></small>
              </h5>
              </a>
              @endforeach
            </div>
            <div class="card-footer">
              <div class="stats">
                <h4>
                <i class="material-icons text-warning">swap_horiz</i>
                <a>Click sobre el estudiante para seleccionarlo</a>
                </h4>
              </div>
            </div>
          </div>
        </div>
        @endif
        @endcan
        @can('family.index')
        <div class="col-lg-4 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-success card-header-icon">
              <div class="card-icon">
                <i class="material-icons">store</i>
              </div>
              <p class="card-category">{{ __('Escuela de familia') }}</p>
              <a href="">
              <h3 class="card-title">Por revisar
                <small></small>
              </h3>
              </a>
              <a href="">
              <h3 class="card-title">(2)
                <small></small>
              </h3>
              </a>
            </div>
            <div class="card-footer">
              <div class="stats">
                <h4>
                <i class="material-icons">date_range</i> Última semana
                </h4>
              </div>
            </div>
          </div>
        </div>
        @endcan
        @can('delays.index')
        <div class="col-lg-4 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-danger card-header-icon">
              <div class="card-icon">
                <i class="material-icons">info_outline</i>
              </div>
              <p class="card-category">{{ __('Control de asistencia') }}</p>
              <a href="">
              <h3 class="card-title">Tardanzas
                <small>({{$data['delays.withoutSign']}})</small>
              </h3>
              </a>
            </div>
            <div class="card-footer">
                 @can('attendance.scan')
              <div class="stats">
              <h4>
                <i class="material-icons text-danger">library_add</i>
                <a href="{{ route('attendance.scan') }}">Escanear</a>
                </h4>
              </div>
              @endcan
            </div>
          </div>
        </div>
        @endcan
    </div>
    <div class="row">
    <div class="col-12">
          <div class="card">
            <div class="card-header card-header-success">
              <h2 class="card-title">Eventos recibidos</h2>
              <h3 class="card-category">Registro de comunicados, tardanzas, etc.</h3>
            </div>
            <div class="card-body table-responsive">
            @include('fullcalendar.master')
            </div>
          </div>
    </div>
    </div>
  </div>
</div>

@push('js')
<script>
  
  document.addEventListener('DOMContentLoaded', function() {
    /* initialize the calendar
    -----------------------------------------------------------------*/
    let colors = new Array();
    colors[1]='blue';
    colors[2]='red';
    colors[3]='red';
    colors[4]='red';
    colors[5]='green';
    colors[6]='green';
    colors[7]='orange';
    colors[8]='green';
    colors[9]='green';
    colors[10]='red';
    colors[11]='yellow';
    colors[12]='red';
    colors[13]='yellow';
    let calendarEl = document.getElementById('calendar');
    let calendar = new FullCalendar.Calendar(calendarEl, {
      locale:'es',
      events: [
        @foreach($statements as $statement)
        {
          title: '{{$types->find($statement->statementtype_id)->name }}',
          start: '{{$statement->created_at}}',
          color: colors[{{$statement->statementtype_id }}],
          textColor: 'black',
          url: '/statements?selected={{$statement->id }}',
          icon : "star"
        },            
        @endforeach
      ],
      plugins: [ 'interaction', 'dayGrid', 'bootstrap' ],
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,dayGridWeek'
      },
      themeSystem: 'bootstrap',
      editable: false,
      droppable: false, // this allows things to be dropped onto the calendar
    });
    calendar.render();
    calendar.on( 'eventClick', function(info) {
      info.jsEvent.preventDefault(); // don't let the browser navigate

      if (info.event.url) {
        location.href = info.event.url;
      }
    });
    calendar.on('dateClick', function(info) {
    //   alert('Clicked on: ' + info.dateStr);
    // alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
    // alert('Current view: ' + info.view.type);
    // // change the day's background color just for fun
    // info.dayEl.style.backgroundColor = 'red';
    });

  });

</script>
@endpush

@endsection
