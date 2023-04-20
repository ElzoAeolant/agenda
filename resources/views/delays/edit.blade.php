@extends('layouts.app', ['activePage' => 'delays_send', 'titlePage' => __('Delay Management')])
@push('head_scripts')
<link rel="stylesheet" href="{{asset('assets/bootstrap-select/css/bootstrap-select.min.css') }}">
@endpush
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <form method="post" action="{{ route('delays.update',['statement'=>$statement]) }}" autocomplete="off" class="form-horizontal">
          @csrf
          @method('put')
          <div class="card ">
            <div class="card-header card-header-{{$showinfo['color']}}">
              <h4 class="card-title">{{ __('Edit Delay') }} : {{ __($showinfo['type'])}}</h4>
              <p class="card-category">Del día {{$showinfo['created_at']}}</p>
            </div>
            <div class="card-body ">
              <div class="row">
                <div class="col-md-12 text-right">
                  <a href="{{ route('delays.index','delays_send') }}" class="btn btn-sm btn-{{$showinfo['color']}}">{{ __('Back to list') }}</a>
                </div>
              </div>
              <div class="row">
                <label class="col-sm-2 col-form-label">{{ __('Salón:') }}</label>
                <div class="col-sm-7">
                  <div class="form-group">
                    <p class="card-category"> {{ $showinfo['classroom'] }}</p>
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-sm-2 col-form-label">{{ __('Destinatario') }}</label>
                <div class="col-sm-6">
                  <div class="form-group">
                    <div class="table-responsive">
                      <table class="table">
                        <thead class=" text-{{$showinfo['color']}}">
                          <th>
                            {{ __('Usuario') }}
                          </th>
                          <th>
                            {{ __('Destinatario') }}
                          </th>
                          <th>
                            {{ __('se dio por leída el día') }}
                          </th>
                        </thead>
                        <tbody>
                          @foreach($showinfo['students'] as $student)
                          <tr>
                            <td>
                              {{$student['username']}}
                            </td>
                            <td>
                              {{$student['name']}}
                            </td>
                            <td class="td-actions text-left">
                              {{ $student['signed_at']}}
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-sm-2 col-form-label">{{ __('Enviado por:') }}</label>
                <div class="col-sm-7">
                  <div class="form-group">
                    <p class="card-category"> {{ $showinfo['owner'] }}</p>
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-sm-2 col-form-label" for="input-details">{{ __('Detalles') }}</label>
                <div class="col-sm-7">
                  <div class="form-group{{ $errors->has('details') ? ' has-danger' : '' }}">
                    <textarea class="form-control" name="details" id="details" placeholder="{{ __('Ingrese el texto') }}" value="" rows="5" />
                    {{$showinfo['details']}}
                    </textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer ml-auto mr-auto">
              <button type="submit" class="btn btn-primary">{{ __('Update Delay') }}</button>
            </div>
          </div>
        </form>
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


    tinymce.init({
      selector: 'textarea#details',
      height: 500,
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

  });
</script>
@endpush
@endsection