@extends('layouts.app', ['activePage' => 'attendance.export', 'titlePage' => __('Attendance Management')])
@push('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-select/css/bootstrap-select.min.css') }}">
@endpush
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ __('Export Attendance') }}</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body ">
                            <form method="get" action="{{ route('attendance.download') }}" autocomplete="off"
                                class="form-horizontal">
                                @csrf
                                @method('get')
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">{{ __('Seleccionar fecha inicial:') }}</label>
                                    <div class="col-sm-6">
                                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                            <input class="form-control" name="d1" id="d1" type="date" value="">
                                            @if ($errors->has('d1'))
                                                <span id="name-error" class="error text-danger"
                                                    for="input-name">{{ $errors->first('d1') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">{{ __('Seleccionar fecha final:') }}</label>
                                    <div class="col-sm-6">
                                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                            <input name="d2" class="form-control" id="d2" type="date" value="">
                                            @if ($errors->has('d2'))
                                                <span id="name-error" class="error text-danger"
                                                    for="input-name">{{ $errors->first('d2') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">{{ __('Seleccionar salón:') }}</label>
                                    <div class="col-sm-7">
                                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                            <select name="classroom" id="classroom"
                                                class="form-control selectpicker show-tick" data-live-search="true"
                                                data-style="btn-primary">
                                                @foreach ($classrooms as $classroom)
                                                    <option value="{{ $classroom->id }}">
                                                        {{ $classroom->level . '_' . $classroom->grade . '_' . $classroom->section }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('classroom'))
                                                <span id="name-error" class="error text-danger"
                                                    for="input-name">{{ $errors->first('classroom') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            <button type="submit"
                                class="btn btn-primary">{{ __('Exportar Excel de Asistencias') }}</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    @push('js')
        <script src="{{ asset('assets/bootstrap-select/js/bootstrap-select.js') }}" referrerpolicy="origin"></script>
        <script src="{{ asset('assets/bootstrap-select/js/i18n/defaults-es_ES.min.js') }}" referrerpolicy="origin"></script>

        <script>
            $(document).ready(function() {
                var edit = '';
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

                window.enableCheckIn = function(id) {
                    $("#registertype_in_" + id).show();
                    $("#attendance_in_" + id).children().last().remove();
                    $("#attendance_in_" + id).removeClass();
                };
                window.enableCheckOut = function(id) {
                    $("#registertype_out_" + id).show();
                    $("#attendance_out_" + id).children().last().remove();
                    $("#attendance_out_" + id).removeClass();
                };

                let OldValue = '{{ old('classroom') }}';
                if (OldValue !== '') {
                    console.log("cambiando la selección.");
                    $('#classroom').val(OldValue);
                }

            });
        </script>
    @endpush
@endsection
