@extends('layouts.app', ['activePage' => $activetab, 'titlePage' => __('Statement Management')])
@push('head_scripts')
<script src="{{asset('js/vendor/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
@endpush
@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header card-header-{{( $activetab=='statements_inbox')?'info':'success'}}">
                <h4 class="card-title ">{{ __('Statements') }} </h4>
                <h1>{{( $activetab=='statements_inbox')?'Recibidos':'Emitidos'}}</h1>
                <p class="card-category"> {{ __('Here you can manage statements') }}</p>
              </div>
              <div class="card-body">
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
                  <div class="col-12 text-right">
                    @can('statements.create')
                    <a href="{{ route('statements.create') }}" class="btn btn-sm btn-success">{{ __('Add Statement') }}</a>
                    @endcan
                    @if( $activetab=='statements_inbox')
                    @can('statements.sign')
                    <a href="{{ route('statements.sign') }}" class="btn btn-sm btn-warning">{{ __('Confirmar lectura') }}</a>
                    @endcan
                    @endif
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table">
                    <thead class=" text-success">
                      <th class="col-lg-11 col-sm-12">
                          {{ __('Detalle') }}
                      </th>
                      @canany(['statements.show','statements.destroy'])
                      @if($activetab=='statements_send')
                      <th class="col-lg-1 col-sm-12">
                          {{ __('Actions') }}
                      </th>
                      @endif
                      @endcanany
                    </thead>
                    <tbody>
                      @forelse($statements as $statement)
                        <tr>
                          <td>
                            {{( $activetab=='statements_inbox')?'Recibido':'Emitido'}}{{' el '.$statement->updated_at.' para: '.$statement->to .' de '. $statement->user_name }}
                            <div class="alert alert-{{$statement->color}} alert-with-icon" data-notify="container">
                              <i class="material-icons" data-notify="icon" style="color: {{$statement->status}};">star</i>
                              <!-- <span data-notify="message"> -->
                                <textarea>
                                  {{$statement->details }}
                                </textarea>
                              <!-- </span> -->
                            </div>
                          </td>
                          @canany(['statements.show','statements.destroy'])
                          @if($activetab=='statements_send')
                          <td class="td-actions text-right">
                            @if ($statement->user_id == auth()->id())
                              <form action="{{ route('statements.destroy', $statement) }}" method="post">
                                  @csrf
                                  @method('delete')
                                  @can('statements.show')
                                  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('statements.show', ['statement'=>$statement]) }}" data-original-title="" title="">
                                    <i class="material-icons">open_in_new</i>
                                    <div class="ripple-container"></div>
                                  </a>
                                  @endcan   
                                  @can('statements.edit')
                                  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('statements.edit', ['statement'=>$statement]) }}" data-original-title="" title="">
                                    <i class="material-icons">edit</i>
                                    <div class="ripple-container"></div>
                                  </a>
                                  @endcan                                   
                                  @can('statements.destroy')
                                  <button type="button" class="btn btn-danger btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this statement?") }}') ? this.parentElement.submit() : ''">
                                      <i class="material-icons">close</i>
                                      <div class="ripple-container"></div>
                                  </button>
                                  @endcan
                              </form>
                              
                            @else
                              @can('statements.show')
                              <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('statements.show', $statement) }}" data-original-title="" title="">
                                <i class="material-icons">open_in_new</i>
                                <div class="ripple-container"></div>
                              </a>
                              @endcan
                            @endif
                          </td>
                          @endif
                          @endcanany
                        </tr>
                      @empty
                      <tr>
                          <td>
                            NO HAY COMUNICADOS
                          </td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                  @if(sizeof($statements))
                  {{ $statements->links() }}
                  @endif
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
        tinymce.init({
          selector: 'textarea',
          //height: 200,
          //widht: 500,
          //max_height: 200,
          theme_advanced_resizing_min_height : 100,
          menubar: false,
          readonly : 0,
          plugins: ["autoresize","print","noneditable"],
          toolbar: "print",
          branding: false,
          body_class : "",
          content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tiny.cloud/css/codepen.min.css'
          ],
          preview_styles:true
        });
      });
    </script>
  @endpush
@endsection