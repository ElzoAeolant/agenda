@extends('layouts.app', ['activePage' => 'attendance.create', 'titlePage' => __('Attendance Management')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header card-header-primary">
            <h4 class="card-title">{{ __('Add Attendance') }}</h4>
            <p class="card-category"></p>
          </div>
          <div class="card-body ">
            <!-- <div class="row">
                <label class="col-sm-2 col-form-label">{{ __('Tipo de registro:') }}</label>
                <div class="col-sm-1">
                  <div class="form-check mr-auto ml-3 mt-3">
                    <label class="form-check-label">
                      <input class="form-check-input" type="radio" name="registertype" checked> {{ __('Manual') }}
                      <span class="form-check-sign">
                        <span class="check"></span>
                      </span>
                    </label>
                  </div>
                </div>
                <div class="col-sm-1">
                  <div class="form-check mr-auto ml-3 mt-3">
                    <label class="form-check-label">
                      <input class="form-check-input" type="radio" name="registertype" {{ old('registertype') ? 'checked' : '' }}> {{ __('Escaneo (Analizar porque no se verá ninguna acción directamente)') }}
                      <span class="form-check-sign">
                        <span class="check"></span>
                      </span>
                    </label>
                  </div>
                </div>
                    @if ($errors->has('registertype'))
                    <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('registertype') }}</span>
                    @endif
              </div> -->
            @can('attendance.changehour')
            <div class="row">
              <label class="col-sm-2 col-form-label">{{ __('Seleccionar hora:') }}</label>
              <div class="col-sm-1">
                <div class="form-check mr-auto ml-3 mt-3">
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" id="isselectedtime"> {{ __('Cambiar') }}
                    <span class="form-check-sign">
                      <span class="check"></span>
                    </span>
                  </label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                  <input class="form-control" type="time" id="selectedtime" value="" disabled>
                  @if ($errors->has('classroom'))
                  <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('classroom') }}</span>
                  @endif
                </div>
              </div>
            </div>
            @endcan
            <div class="row">
              <label class="col-sm-2 col-form-label">{{ __('Seleccionar salón:') }}</label>
              <div class="col-sm-7">
                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                  <select name="classroom" id="classroom" class="form-control">
                    <option value="">--- Seleccionar Salón ---</option>
                    @foreach ($classrooms as $classroom)
                    <option value="{{$classroom->id}}">{{$classroom->level."_".$classroom->grade."_".$classroom->section}}</option>
                    @endforeach
                  </select>
                  @if ($errors->has('classroom'))
                  <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('classroom') }}</span>
                  @endif
                </div>
              </div>
            </div>
            <!-- <div class="row">
                <label class="col-sm-2 col-form-label">{{ __('Seleccionar bimestre(Analizar esta opción):') }}</label>
                <div class="col-sm-7">
                  <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                    <select name="classroom" id="classroom" class="form-control">
                      <option value="">--- Seleccionar Bimestre ---</option>
                      
                    </select> 
                    @if ($errors->has('classroom'))
                    <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('classroom') }}</span>
                    @endif
                  </div>
                </div>
              </div> -->
            <div class="row">
              <label class="col-sm-2 col-form-label">{{ __('Alumno') }}</label>
              <div class="col-sm-6">
                <div class="form-group">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-info">
                        <th>
                          {{ __('Alumno') }}
                        </th>
                        <th class="attendancecheckin" data-in=0>
                          {{ __('Entrada') }}
                        </th>
                        <th class="attendancecheckout" data-in=1>
                          {{ __('Salida') }}
                        </th>
                      </thead>
                      <tbody id="studentList">
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer ml-auto mr-auto">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@push('js')
<script>
  $(document).ready(function() {
    function showNotification(from, align, message, type) {
      $.notify({
        icon: "add_alert",
        message: message

      }, {
        type: type,
        timer: 0.5,
        placement: {
          from: from,
          align: align
        }
      });
    }
    
    function getLimaTime() {
        let offset = '-5.0' ;
        // create Date object for current location
        var d = new Date();

        // convert to msec
        // subtract local time zone offset
        // get UTC time in msec
        var utc = d.getTime() + (d.getTimezoneOffset() * 60000);

        // create new Date object for different city
        // using supplied offset
        var nd = new Date(utc + (3600000*offset));

        // return time as a string
        return ("00" + nd.getHours()).slice (-2)+":"+("00" + nd.getMinutes()).slice (-2);
    }

    $('#isselectedtime').on('change', function() {
      if(this.checked){
        $('#selectedtime').prop("disabled", false);
        $('#selectedtime').val(getLimaTime());
      }else{
        $('#selectedtime').prop("disabled", true);
        $('#selectedtime').val('');
      }
    });

    $('#studentList').on('click', '.attendancecheckin', function() {
      //Se registra la asistencia del alumno.
      $.ajax({
        type: 'GET',
        url: '/ajaxrequest',
        data: {
          method: 'storeattendance',
          register: this.dataset.in,
          registerfor: 'checkin',
          type : 'Manual',
          @can('attendance.changehour')
          isselectedtime: $('#isselectedtime').is(":checked"),
          selectedtime: $('#selectedtime').val()
          @endcan
        },
        success: function(response) {
          console.log(response)
          if (response.success) {
            //Se marca la hora en la que el servidor registró la asistencia y el color del texto
            $("#attendance_in_" + response.id).addClass("text-"+response.color);
            let editbutton = '<a rel="tooltip" class="btn btn-success btn-link"  href="#" onclick="enableCheckIn('+response.id+')" data-original-title="" title="">'+
                             '<i class="material-icons">open_in_new</i>'+
                             '<div class="ripple-container"></div>'+
                             '</a>';
            $("#attendance_in_" + response.id).append('<div id="todelete">'+response.time+editbutton+'</div>');
            $("#registertype_in_" + response.id).hide();
            showNotification('top', 'center',  response.msg + response.for , 'success')
          } else {
            let errors = JSON.parse(response.errors);
            let msgError = '';
            for (var key in errors) {
                if (errors.hasOwnProperty(key)) {
                    msgError+='Error: ' + errors[key] + '\n';
                }
            }
            //$("registertype_" + response.id).prop('checked', false);
            showNotification('top', 'center', msgError, 'rose')
          }
        },
        error: function(error) {
          console.log(error.responseJSON.message);
        },
      });
    });


    $('#studentList').on('click', '.attendancecheckout', function() {
      //Se registra la asistencia del alumno.
      $.ajax({
        type: 'GET',
        url: '/ajaxrequest',
        data: {
          method: 'storeattendance',
          register: this.dataset.out,
          registerfor: 'checkout',
          type : 'Manual',
          @can('attendance.changehour')
          isselectedtime: $('#isselectedtime').is(":checked"),
          selectedtime: $('#selectedtime').val()
          @endcan
        },
        success: function(response) {
          console.log(response)
          if (response.success) {
            //Se marca la hora en la que el servidor registró la asistencia y el color del texto
            let editbutton = '<a rel="tooltip" class="btn btn-success btn-link"  href="#" onclick="enableCheckOut('+response.id+')" data-original-title="" title="">'+
                             '<i class="material-icons">open_in_new</i>'+
                             '<div class="ripple-container"></div>'+
                             '</a>';
            $("#attendance_out_" + response.id).addClass("text-"+response.color);
            $("#attendance_out_" + response.id).append('<div id="todelete">'+response.time+editbutton+'</div>');
            $("#registertype_out_" + response.id).hide();
            showNotification('top', 'center', response.msg + response.for, 'success')
          } else {
            let errors = JSON.parse(response.errors);
            let msgError = '';
            for (var key in errors) {
                if (errors.hasOwnProperty(key)) {
                    msgError+='Error: ' + errors[key] + '\n';
                }
            }
            //$("registertype_" + response.id).prop('checked', false);
            showNotification('top', 'center', msgError, 'rose')
          }
        },
        error: function(error) {
          console.log(error.responseJSON.message);
        },
      });
    });

    $('#classroom').change(function() {
      $.ajax({
        type: 'GET',
        url: '/ajaxrequest',
        data: {
          method: 'getstudentsattendance',
          param: $(this).val()
        },
        success: function(response) {
          if (response.success != null) {
            let students = JSON.parse(response.data);
            let len = students.length;
            let student = $("#studentList");
            student.empty();
            for (let i = 0; i < len; i++) {
              let id = students[i]['id'];
              let name = students[i]['name'];
              let attendance_in = students[i]['attendance_in'];
              let attendance_color_in = students[i]['checkin_color'];
              let attendance_out = students[i]['attendance_out'];
              let attendance_color_out = students[i]['checkout_color'];

              let editbuttonIn = '<a rel="tooltip" class="btn btn-success btn-link"  href="#" onclick="enableCheckIn('+id+')" data-original-title="" title="">'+
                             '<i class="material-icons">open_in_new</i>'+
                             '<div class="ripple-container"></div>'+
                             '</a>';

              let checkBoxIn =  "<div class='form-check mr-auto ml-3 mt-3' "+((attendance_in != '')?"style='display: none;'":'') +" id='registertype_in_" + id + "'>" +
                  "<label class='form-check-label'>" +
                  "<input class='form-check-input attendancecheckin' type='checkbox' data-in='" + id + "' >" +
                  "<span class='form-check-sign'>" +
                  "<span class='check'></span>" +
                  "</span>" +
                  "</label>" +
                  "</div>";

              let editbuttonOut = '<a rel="tooltip" class="btn btn-success btn-link"  href="#" onclick="enableCheckOut('+id+')" data-original-title="" title="">'+
                             '<i class="material-icons">open_in_new</i>'+
                             '<div class="ripple-container"></div>'+
                             '</a>';
              let checkBoxOut = "<div class='form-check mr-auto ml-3 mt-3' "+((attendance_out != '')?"style='display: none;'":'') +"id='registertype_out_" + id + "'>" +
                  "<label class='form-check-label'>" +
                  "<input class='form-check-input attendancecheckout' type='checkbox' data-out='" + id + "' >" +
                  "<span class='form-check-sign'>" +
                  "<span class='check'></span>" +
                  "</span>" +
                  "</label>" +
                  "</div>";;

              if (attendance_in == '') {
                attendance_in = checkBoxIn;
              }else{
                attendance_in = checkBoxIn+'<div id="todelete">'+attendance_in+editbuttonIn+'</div>'
              }
              if (attendance_out == '') {
                attendance_out =checkBoxOut;
              }else{
                attendance_out = checkBoxOut+'<div id="todelete">'+attendance_out+editbuttonOut+'</div>'
              }
              student.append("<tr>" + "<td>" + id + "_" + name + "</td>" + "<td class='text-"+attendance_color_in+"' id='attendance_in_"+id+"'>" + attendance_in + "</td>" + "<td class='text-"+attendance_color_out+"'id='attendance_out_"+id+"'>" + attendance_out + "</td>" + "</tr>");
            }

          }
        },
        error: function(error) {
          console.log(error.responseJSON.message);
        }
      });
    });
    
    window.enableCheckIn = function(id){
      $("#registertype_in_" + id).show();
      $("#attendance_in_" + id).children().last().remove();
      $("#attendance_in_" + id).removeClass();
    };
    window.enableCheckOut = function(id){
      $("#registertype_out_" + id).show();
      $("#attendance_out_" + id).children().last().remove();
      $("#attendance_out_" + id).removeClass();
    };

    
    

    let OldValue = '{{ old("classroom") }}';
    if (OldValue !== '') {
      console.log("cambiando la selección.");
      $('#classroom').val(OldValue);
    }

  });
</script>
@endpush
@endsection