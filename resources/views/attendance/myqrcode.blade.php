@extends('layouts.app', ['activePage' => 'attendance.myqr' , 'titlePage' => __('Attendance Management')])
@push('head_scripts')
<style>
    .toprint {
        margin: 5 auto;
        display: block;
        overflow: visible !important;
    }

    .wrap {
        
    }

    .hide-scrollbar {
        overflow: -moz-hidden-unscrollable;
        overflow: hidden;
    }
</style>

@endpush
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header card-header-info">
                        <h4 class="card-title">Código QR</h4>
                        <p class="card-category">Listo para escanear su asistencia</p>
                    </div>
                    <div class="card-body" id="studentList">
                        @foreach($users as $user)
                        <div class="row wrap">
                            <!-- <label class="col-sm-2 col-form-label">{{ __('QR de Usuarios') }}</label> -->
                            <div   class="col-sm-4">
                                <div class="form-group">
                                    <div  id="toprint_{{$user->id}}" class="toprint text-center">
                                        <div>Colegio Jesús el Buen Pastor</div>
                                        <div>{{$user->name}}</div>
                                        <figure>
                                            <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->color(0, 0, 128)->errorCorrection('H')->merge('https://agenda.jebp.smarteduperu.com/material/img/jebp.png', .2, true)->size(250)->margin(0)->generate($user->username)) !!}">
                                            <figcaption>{{$user->username}}</figcaption>
                                        </figure>
                                    </div>
                                    @if((new \Jenssegers\Agent\Agent())->isDesktop())
                                        <div class="text-center">
                                            <div data-id={{$user->id}} data-name={{$user->username}} class="btn download">Descargar</div>
                                        </div>
                                        @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@if((new \Jenssegers\Agent\Agent())->isDesktop())
@push('js')
<script src="{{asset('assets/html2canvas.min.js') }}" referrerpolicy="origin"></script>
<script>
    $(document).ready(function() {
        $('#studentList').on('click', '.download', function() {
            console.log(this.dataset.id);
            var id = this.dataset.name
            let canvas = $('#toprint_' + this.dataset.id)[0];
            let prevScrllH = canvas.scrollHeight;
            window.scrollTo(0,0);  
            html2canvas(canvas,{
                windowHeight: canvas.scrollHeight}
                ).then(function(canvas) {
                let a = document.createElement('a');
                // toDataURL defaults to png, so we need to request a jpeg, then convert for file download.
                a.href = canvas.toDataURL("image/jpeg").replace("image/jpeg", "image/octet-stream");
                a.download = 'myqr' + id + '.jpg';
                a.click();
            });
            window.scrollTo(0, prevScrllH);
        });
    });
</script>
@endpush
@endif
@endsection
