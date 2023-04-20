@extends('layouts.app', ['activePage' => 'statements_send', 'titlePage' => __('Statement Management')])
@push('head_scripts')
<script src="{{asset('js/vendor/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
@endpush
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <form method="post" action="{{ route('statements.store') }}" autocomplete="off" class="form-horizontal">
          @csrf
          @method('post')
          <div class="card ">
            <div class="card-header card-header-primary">
              <h4 class="card-title">{{ __('Add Statement') }}</h4>
              <p class="card-category"></p>
            </div>
            <div class="card-body ">
              <div class="row">
                <div class="col-md-12 text-right">
                  <a href="{{ route('statements.index','statements_send') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                </div>
              </div>
              @if ( !Auth::user()->hasRole('PPFF'))
              <div class="row">
                <label class="col-sm-2 col-form-label">{{ __('Seleccionar salón:') }}</label>
                <div class="col-sm-7">
                  <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                    <select name="classroom" id="classroom" class="form-control">
                      <option value="">--- Seleccionar Salón ---</option>
                      @foreach ($classrooms as $classroom)
                      <option value="{{$classroom->id}}" >{{$classroom->level."_".$classroom->grade."_".$classroom->section}}</option>
                      @endforeach
                    </select>

                    @if ($errors->has('classroom'))
                    <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('classroom') }}</span>
                    @endif
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-sm-2 col-form-label">{{ __('Seleccionar destinatario:') }}</label>
                <div class="col-sm-1">
                  <div class="form-check mr-auto ml-3 mt-3">
                    <label class="form-check-label">
                      <input class="form-check-input" type="checkbox" name="all" {{ old('all') ? 'checked' : '' }}> {{ __('Todos') }}
                      <span class="form-check-sign">
                        <span class="check"></span>
                      </span>
                    </label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                    <select name="student_id[]" id="student_id" class="form-control" multiple data-live-search="true">
                      <option>-- Seleccionar destinatario --</option>
                    </select>
                    @if ($errors->has('student_id'))
                    <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('student_id') }}</span>
                    @endif
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-sm-2 col-form-label">{{ __('Seleccionar tipo') }}:</label>
                <div class="col-sm-7">
                  <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                    <select name="statementtype_id" id="statementtype_id" class="custom-select">
                      <option value=''>{{ __('Seleccionar tipo') }}</option>
                      @isset($types)
                      @foreach ($types as $type)
                      <option class="text-{{$type->color}}" value="{{$type->id}}">{{$type->name}}</option>
                      @endforeach
                      @endisset
                    </select>
                    @if ($errors->has('statementtype_id'))
                    <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('statementtype_id') }}</span>
                    @endif
                  </div>
                </div>
              </div>
              @endif
              <div class="row">
                <label class="col-sm-2 col-form-label" for="input-details">{{ __('Ingrese el texto') }}</label>
                <div class="col-sm-7">
                  <div class="form-group{{ $errors->has('details') ? ' has-danger' : '' }}">
                    <textarea class="form-control{{ $errors->has('details') ? ' is-invalid' : '' }}" name="details" id="details" placeholder="{{ __('Ingrese el texto') }}" value="" rows="10" />
                    </textarea>
                    @if ($errors->has('details'))
                    <span id="name-error" class="error text-danger" for="input-details">{{ $errors->first('details') }}</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer ml-auto mr-auto">
              <button type="submit" class="btn btn-primary">{{ __('Add Statement') }}</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@push('js')
<script>
    $(document).ready(function() {
     
      tinymce.init({
        selector: 'textarea#details',
        height: 500,
        menubar: false,
        readonly : 0,
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

      $('#classroom').change(function(){
        $.ajax({
          type:'GET',
          url:'/ajaxrequest',
          data:{method:'getstudents', param:$( this ).val()},
            success:function(response){
              if (response.success!=null){
                  let students = JSON.parse(response.data);
                  let len = students.length;
                  let student = $("#student_id");
                  student.empty();
                  student.append("<option>--Seleccionar destinatario--</option>");
                  for( let i = 0; i<len; i++){
                      let id = students[i]['id'];
                      let name = students[i]['name'];
                      student.append("<option value='"+id+"'>"+name+"</option>");
                  }
              }
            },
            error:function(error){
              console.log(error.responseJSON.message);
            }
          });
      });

      let OldValue = '{{ old('classroom') }}';
      if(OldValue !== '') {
        console.log("cambiando la selección.");
        $('#classroom').val(OldValue );
      }
      
    });
  </script>
@endpush
@endsection