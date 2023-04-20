@extends('layouts.app', ['activePage' => 'familyschools.scan' , 'titlePage' => __('Family Schools Management')])
@push('head_scripts')
<style>
  video {
  width: 100%    !important;
  height: auto   !important;
}
</style>
<script type="text/javascript" src="{{asset('js/vendor/instascan/instascan.min.js') }}"></script>
@endpush
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header card-header-info">
            <h4 class="card-title">Escaneando QR</h4>
            <p class="card-category">Del día {{now()}}</p>
          </div>
          <div class="card-body ">
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
                <label class="col-sm-2 col-form-label">{{ __('Tipo de registro:') }}</label>
                <div class="col-sm-1">
                  <div class="form-check mr-auto ml-3 mt-3">
                    <label class="form-check-label">
                      <input class="form-check-input" type="radio" name="registertype" value="checkin" {{ date('H') < 13 ? 'checked' : '' }}> {{ __('Entrada') }}
                      <span class="form-check-sign">
                        <span class="check"></span>
                      </span>
                    </label>
                  </div>
                </div>
                <div class="col-sm-1">
                  <div class="form-check mr-auto ml-3 mt-3">
                    <label class="form-check-label">
                      <input class="form-check-input" type="radio" name="registertype" value="checkout" {{ date('H') > 13 ? 'checked' : '' }}> {{ __('Salida') }}
                      <span class="form-check-sign">
                        <span class="check"></span>
                      </span>
                    </label>
                  </div>
                </div>
                    @if ($errors->has('registertype'))
                    <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('registertype') }}</span>
                    @endif
              </div>
            <div class="row">
            <label class="col-sm-2 col-form-label">{{ __('Escanear:') }}</label>
              <div class="col-sm-1">
              </div>
              <div class="col-sm-6">
              <video id="preview" ></video>
              </div>
            </div>
            <div class="row">
              <label class="col-sm-2 col-form-label">{{ __('Alumnos Escaneados') }}</label>
              <div class="col-sm-6">
                <div class="form-group">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-info">
                        <th>
                          {{ __('Alumno') }}
                        </th>
                        <th>
                          {{ __('Registro') }}
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
      let offset = '-5.0';
      // create Date object for current location
      var d = new Date();

      // convert to msec
      // subtract local time zone offset
      // get UTC time in msec
      var utc = d.getTime() + (d.getTimezoneOffset() * 60000);

      // create new Date object for different city
      // using supplied offset
      var nd = new Date(utc + (3600000 * offset));

      // return time as a string
      return ("00" + nd.getHours()).slice(-2) + ":" + ("00" + nd.getMinutes()).slice(-2);
    }

    $('#isselectedtime').on('change', function() {
      if (this.checked) {
        $('#selectedtime').prop("disabled", false);
        $('#selectedtime').val(getLimaTime());
      } else {
        $('#selectedtime').prop("disabled", true);
        $('#selectedtime').val('');
      }
    });

    let scanner = new Instascan.Scanner({
      video: document.getElementById('preview'),
      mirror: false
    });
    scanner.addListener('scan', function(content) {
      $.ajax({
        type: 'GET',
        url: '/ajaxrequest',
        data: {
          method: 'storeattendance',
          register: content, 
          registerfor: $("input[name='registertype']:checked").val(),
          type : 'Scan',
          @can('attendance.changehour')
          isselectedtime: $('#isselectedtime').is(":checked"),
          selectedtime: $('#selectedtime').val()
          @endcan
        },
        success: function(response) {
          console.log(response)
          if (response.success) {
            let studentList = $("#studentList");
            studentList.append("<tr>" + "<td>" + response.for + "</td>" + "<td class='text-"+response.color+"'>" +response.msg+" a las : "+response.time+ "</td>" + "</tr>");
          } else {
            let errors = JSON.parse(response.errors);
            let msgError = '';
            for (var key in errors) {
              if (errors.hasOwnProperty(key)) {
                msgError += 'Error: ' + errors[key] + '\n';
              }
            }
          }
        },
        error: function(error) {
          console.log(error.responseJSON.message);
        },
      });      
    });
    Instascan.Camera.getCameras().then(function(cameras) {
      if (cameras.length > 0) {
        scanner.start(cameras[0]);
      } else {
        console.error('No cameras found.');
      }
    }).catch(function(e) {
      console.error(e);
    });
  });
</script>
@endpush
@endsection