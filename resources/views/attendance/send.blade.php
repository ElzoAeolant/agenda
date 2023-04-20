@extends('layouts.app', ['activePage' => 'attendance.send', 'titlePage' => __('Attendance Management')])
@push('head_scripts')
<link rel="stylesheet" href="{{asset('assets/bootstrap-select/css/bootstrap-select.min.css') }}">
<link href="{!! asset('assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css') !!}" rel="stylesheet" />
@endpush
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">{{ __('Add Attendance') }}</h4>
                        <p class="card-category">Se enviarán comunicados de inasistencia y tardanzas.</p>
                    </div>
                    <div class="card-body ">
                        @if (session('status'))
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="alert alert-{{explode('-',session('status'))[0]}}">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <i class="material-icons">close</i>
                                    </button>
                                    <span>{{ explode('-',session('status'))[1] }}</span>
                                </div>
                            </div>
                        </div>
                        @endif


                        <div class="row">
                            <label class="col-sm-2 col-form-label" for="input-date">{{ __('Fecha') }}</label>
                            <div class="col-sm-6">
                                <input type='text' class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" id='datetimepicker' class="form-control">
                                @if ($errors->has('date'))
                                <span id="name-error" class="error text-danger" for="input-date">{{ $errors->first('date') }}</span>
                                @endif
                            </div>
                        </div>
                        <form method="post" action="{{ route('attendance.emit') }}" class="form-horizontal">
                            @csrf
                            @method('post')
                            <input type='hidden' name="date" id="date">
                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Seleccionar salón:') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <select name="classroom" id="classroom" class="form-control selectpicker show-tick" data-live-search="true" data-style="btn-primary">
                                            @foreach ($classrooms as $classroom)
                                            <option value="{{$classroom->id}}">{{$classroom->level."_".$classroom->grade."_".$classroom->section}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    @if ($errors->has('classroom'))
                                    <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('classroom') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!--<div class="row">
                                <label class="col-sm-2 col-form-label" for="input-details">{{ __('Ingrese el mensaje a enviar') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('details') ? ' has-danger' : '' }}">
                                        <textarea class="form-control{{ $errors->has('details') ? ' is-invalid' : '' }}" name="details" id="details" placeholder="{{ __('Ingrese el texto') }}" value="" rows="10" />
                                        {{ old("details") }}
                                        </textarea>
                                        @if ($errors->has('details'))
                                        <span id="name-error" class="error text-danger" for="input-details">{{ $errors->first('details') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>-->
                            <div class="row">
                                <div class="col-12 text-center">
                                    @can('attendance.send')
                                    <button type="submit" class="btn btn-success">{{ __('Enviar Comunicados') }}</button>
                                    @endcan
                                </div>
                            </div>
                        </form>
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
<script src="{{asset('js/vendor/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script src="{{asset('assets/bootstrap-select/js/bootstrap-select.js') }}" referrerpolicy="origin"></script>
<script src="{{asset('assets/bootstrap-select/js/i18n/defaults-es_ES.min.js') }}" referrerpolicy="origin"></script>
<script>
    $(document).ready(function() {
        @can('attendance.edit')
        var edit = 'Editar';
        @else
        var edit = '';
        @endcan
        // To style all selects
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
            $('select').selectpicker('mobile');
            $('select').selectpicker('val', '');
            $('select').selectpicker('refresh');
        } else {
            $('select').selectpicker();
            $('select').selectpicker('val', '');
            $('select').selectpicker('refresh');
        }
        tinymce.init({
            selector: 'textarea#details',
            height: 250,
            menubar: false,
            readonly: 0,
            mobile: {
                plugins: 'print preview importcss searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern noneditable help charmap quickbars  emoticons '
            },
            plugins: 'print preview importcss searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help  charmap quickbars  emoticons',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor casechange removeformat | pagebreak | charmap emoticons | fullscreen  preview print | insertfile image media template link anchor codesample | ltr rtl | showcomments addcomment',
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tiny.cloud/css/codepen.min.css'
            ]
        });
        $('#datetimepicker').datetimepicker({
            format: 'DD-MM-YYYY',
        });
        var d = new Date();

        var month = d.getMonth() + 1;
        var day = d.getDate();

        var output = (day < 10 ? '0' : '') + day + '-' +
            (month < 10 ? '0' : '') + month + '-' + d.getFullYear();
        let OldDate = '{{ old("date") }}';
        $("#datetimepicker").val(output);
        $('#date').val(output);
        if (OldDate !== '') {
            $("#datetimepicker").val(OldDate);
            $('#date').val(OldDate);
        }


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

        window.toogle_in_justification = function(id) {
            @can('attendance.edit')
            //Se registra la asistencia del alumno.
            $.ajax({
                type: 'GET',
                url: '/ajaxrequest',
                data: {
                    method: 'updateattendance',
                    register: id,
                    registerfor: 'checkin',
                },
                success: function(response) {
                    console.log(response)
                    if (response.success) {
                        $("#attendance_in_" + response.id).children().last().remove();
                        $("#attendance_in_" + response.id).removeClass();
                        //Se marca la hora en la que el servidor registró la asistencia y el color del texto
                        $("#attendance_in_" + response.id).addClass("text-" + response.color);
                        let editbutton = '<a rel="tooltip" class="btn ' + (response.is_justified ? 'btn-success' : 'btn-warning') + ' btn-link"  href="#" onclick="toogle_in_justification(' + response.id + ')" data-original-title="" title="">' +
                            '<i class="material-icons">' + (response.is_justified ? 'how_to_reg' : 'alarm_off') + '</i>' +
                            '<div class="ripple-container">' + edit + '</div>' +
                            '</a>';
                        $("#attendance_in_" + response.id).append('<div id="todelete">' + response.time + editbutton + '</div>');
                        showNotification('top', 'center', response.msg + response.time + ' para ' + response.for, 'success')
                    } else {
                        let errors = JSON.parse(response.errors);
                        let msgError = '';
                        for (var key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                msgError += 'Error: ' + errors[key] + '\n';
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
            @else
            showNotification('top', 'center', "No se tienen los permisos requeridos para editar" , 'warning')
            @endcan
        }


        window.toogle_out_justification = function(id) {
            @can('attendance.edit')
            //Se registra la asistencia del alumno.
            $.ajax({
                type: 'GET',
                url: '/ajaxrequest',
                data: {
                    method: 'updateattendance',
                    register: id,
                    registerfor: 'checkout',
                },
                success: function(response) {
                    console.log(response)
                    if (response.success) {
                        $("#attendance_out_" + response.id).children().last().remove();
                        $("#attendance_out_" + response.id).removeClass();
                        //Se marca la hora en la que el servidor registró la asistencia y el color del texto
                        let editbutton = '<a rel="tooltip" class="btn ' + (response.is_justified ? 'btn-success' : 'btn-warning') + ' btn-link"  href="#" onclick="toogle_out_justification(' + response.id + ')" data-original-title="" title="">' +
                            '<i class="material-icons">' + (response.is_justified ? 'how_to_reg' : 'alarm_off') + '</i>' +
                            '<div class="ripple-container">' + edit + '</div>' +
                            '</a>';

                        $("#attendance_out_" + response.id).addClass("text-" + response.color);
                        $("#attendance_out_" + response.id).append('<div id="todelete">' + response.time + editbutton + '</div>');
                        $("#registertype_out_" + response.id).hide();
                        showNotification('top', 'center', response.msg + response.time + ' para ' + response.for, 'success')
                    } else {
                        let errors = JSON.parse(response.errors);
                        let msgError = '';
                        for (var key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                msgError += 'Error: ' + errors[key] + '\n';
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
            @else
            showNotification('top', 'center', "No se tienen los permisos requeridos para editar" , 'warning')
            @endcan
        }

        function getData() {
            $.ajax({
                type: 'GET',
                url: '/ajaxrequest',
                data: {
                    method: 'getstudentsDelays',
                    param: $('#classroom').val(),
                    date: $('#datetimepicker').val()
                },
                success: function(response) {
                    if (response.success != null) {
                        let students = JSON.parse(response.data);
                        let len = students.length;
                        let student = $("#studentList");
                        student.empty();
                        for (let i = 0; i < len; i++) {
                            let id = students[i]['attendance_id'];
                            let attendance_notified = students[i]['attendance_notified'];
                            let name = students[i]['name'];
                            let attendance_in = students[i]['attendance_in'];
                            let attendance_color_in = students[i]['checkin_color'];
                            let attendance_in_justified = students[i]['attendance_in_justified'];
                            let attendance_out = students[i]['attendance_out'];
                            let attendance_color_out = students[i]['checkout_color'];
                            let attendance_out_justified = students[i]['attendance_out_justified'];

                            let editbuttonIn = '<a rel="tooltip" class="btn ' + (attendance_in_justified ? 'btn-success' : 'btn-warning') + ' btn-link"  href="#" onclick="toogle_in_justification(' + id + ')" data-original-title="" title="">' +
                                '<i class="material-icons">' + (attendance_in_justified ? 'how_to_reg' : 'alarm_off') + '</i>' +
                                '<div class="ripple-container">' + edit + '</div>' +
                                '</a>';
                            let notified = '<a rel="tooltip" class="btn ' + (attendance_notified ? 'btn-success' : 'btn-danger') + ' btn-link"  href="#" onclick="toogle_in_justification(' + id + ')" data-original-title="" title="">' +
                                '<i class="material-icons">' + (attendance_notified ? 'mobile_friendly' : 'mobile_off') + '</i>' +
                                '<div class="ripple-container"></div>' +
                                '</a>';
                            let checkBoxIn = "---";

                            let editbuttonOut = '<a rel="tooltip" class="btn ' + (attendance_out_justified ? 'btn-success' : 'btn-warning') + ' btn-link"  href="#" onclick="toogle_out_justification(' + id + ')" data-original-title="" title="">' +
                                '<i class="material-icons">' + (attendance_out_justified ? 'how_to_reg' : 'alarm_off') + '</i>' +
                                '<div class="ripple-container">' + edit + '</div>' +
                                '</a>';
                            let checkBoxOut = "---";

                            if (attendance_in == '') {
                                attendance_in = checkBoxIn;
                            } else {
                                if (students[i]['in_require_justification']) {
                                    attendance_in = '<div id="todelete">' + attendance_in + editbuttonIn + '</div>'
                                } else {
                                    attendance_in = '<div id="todelete">' + attendance_in + '</div>'
                                }
                            }
                            if (attendance_out == '') {
                                attendance_out = checkBoxOut;
                            } else {
                                if (students[i]['out_require_justification']) {
                                    attendance_out = '<div id="todelete">' + attendance_out + editbuttonOut + '</div>'
                                } else {
                                    attendance_out = '<div id="todelete">' + attendance_out + '</div>'
                                }
                            }
                            student.append("<tr>" + "<td>" + notified+name + "</td>" + "<td class='text-" + attendance_color_in + "' id='attendance_in_" + id + "'>" + attendance_in + "</td>" + "<td class='text-" + attendance_color_out + "'id='attendance_out_" + id + "'>" + attendance_out + "</td>" + "</tr>");
                        }

                    }
                },
                error: function(error) {
                    console.log(error.responseJSON.message);
                }
            });
        }

        $('#classroom').change(function() {
            getData();
        });

        $('#datetimepicker').on('dp.change', function(e) {
            $('#date').val(e.date.format('DD-MM-YYYY'))
            if (e.oldDate != e.date && $('#classroom').val() != null) {
                getData();
            }
        });
        let OldValue = '{{ old("classroom") }}';
        if (OldValue !== '') {
            $('#classroom').val(OldValue);
            $('#classroom').selectpicker('refresh');
            getData();
        }
    });
</script>
@endpush
@endsection