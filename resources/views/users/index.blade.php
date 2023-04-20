@extends('layouts.app', ['activePage' => 'user-management', 'titlePage' => __('User Management')])
@push('head_scripts')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css"/>
@endpush
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-success">
            <h4 class="card-title ">{{ __('Users') }}</h4>
            <p class="card-category"> {{ __('Here you can manage users') }}</p>
          </div>
          <div class="card-body">
            @if (session('status'))
            <div class="row">
              <div class="col-sm-12">
                <div class="alert alert-success">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="material-icons">close</i>
                  </button>
                  <span>{{ session('status') }}</span>
                </div>
              </div>
            </div>
            @endif
            <div class="row">
              <div class="col-12 text-right">
                <a href="{{ route('user.create') }}" class="btn btn-sm btn-success">{{ __('Add User') }}</a>
              </div>
            </div>
            <div class="table-responsive">
              <table id="dtBasicExample" class="table table-striped table-bordered table-sm display responsive " cellspacing="0" width="100%">
                <thead class=" text-success">
                  <th>No</th>
                  <th>
                    {{ __('Name') }}
                  </th>
                  <th>
                    {{ __('Email') }}
                  </th>
                  <th>
                    {{ __('Creation date') }}
                  </th>
                  <th>
                    {{ __('Roles') }}
                  </th>
                  <th class="text-right">
                    {{ __('Actions') }}
                  </th>
                </thead>
                <tbody>
                  @foreach($users as $user)
                  <tr>
                    <td>{{ ++$i }}</td>
                    <td>
                      {{ $user->name }}
                    </td>
                    <td>
                      {{ $user->email }}
                    </td>
                    <td>
                      {{ $user->created_at->format('Y-m-d') }}
                    </td>
                    <td>
                      @if(!empty($user->getRoleNames()))
                      @foreach($user->getRoleNames() as $v)
                      <label class="badge badge-success">{{ $v }}</label>
                      @endforeach
                      @endif
                    </td>
                    <td class="td-actions text-right">
                      @if ($user->id != auth()->id())
                      <form action="{{ route('user.destroy', $user) }}" method="post">
                        @csrf
                        @method('delete')
                        <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('user.edit', $user) }}" data-original-title="" title="">
                          <i class="material-icons">edit</i>
                          <div class="ripple-container"></div>
                        </a>
                        <button type="button" class="btn btn-danger btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this user?") }} : {{ $user->email }} ') ? this.parentElement.submit() : ''">
                          <i class="material-icons">close</i>
                          <div class="ripple-container"></div>
                        </button>
                      </form>
                      @else
                      <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('profile.edit') }}" data-original-title="" title="">
                        <i class="material-icons">edit</i>
                        <div class="ripple-container"></div>
                      </a>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              {!! $users->render() !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@push('js')
<script src="{{ asset('material') }}/js/plugins/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#dtBasicExample').DataTable();
    $('.dataTables_length').addClass('bs-select');
  });
</script>
@endpush
@endsection