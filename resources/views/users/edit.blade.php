@extends('layouts.app', ['activePage' => 'user-management', 'titlePage' => __('User Management')])
@push('head_scripts')
<link rel="stylesheet" href="{{asset('assets/bootstrap-select/css/bootstrap-select.min.css') }}">
@endpush
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <form method="post" action="{{ route('user.update', $user) }}" autocomplete="off" class="form-horizontal">
          @csrf
          @method('put')

          <div class="card ">
            <div class="card-header card-header-primary">
              <h4 class="card-title">{{ __('Edit User') }}</h4>
              <p class="card-category"></p>
            </div>
            <div class="card-body ">
              <div class="row">
                <div class="col-md-12 text-right">
                  <a href="{{ route('user.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                </div>
              </div>
              <div class="row">
                <label class="col-sm-2 col-form-label">{{ __('Name') }}</label>
                <div class="col-sm-7">
                  <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name', $user->name) }}" />
                    @if ($errors->has('name'))
                    <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                    @endif
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-sm-2 col-form-label">{{ __('Usuario') }}</label>
                <div class="col-sm-7">
                  <div class="form-group{{ $errors->has('username') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" id="input-username"  placeholder="{{ __('Usuario') }}" value="{{ old('username', $user->username) }}" />
                    @if ($errors->has('username'))
                    <span id="username-error" class="error text-danger" for="input-username">{{ $errors->first('username') }}</span>
                    @endif
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-sm-2 col-form-label" for="input-password">{{ __('Password') }}</label>
                <div class="col-sm-7">
                  <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" input type="password" name="password" id="input-password" placeholder="{{ __('Password') }}" />
                    @if ($errors->has('password'))
                    <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('password') }}</span>
                    @endif
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-sm-2 col-form-label" for="input-password-confirmation">{{ __('Confirm Password') }}</label>
                <div class="col-sm-7">
                  <div class="form-group">
                    <input class="form-control" name="password_confirmation" id="input-password-confirmation" type="password" placeholder="{{ __('Confirm Password') }}" />
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-sm-2 col-form-label">{{ __('Roles') }}</label>
                <div class="col-sm-7">
                  <div class="form-group{{ $errors->has('roles') ? ' has-danger' : '' }}">
                    <select name="roles[]" id="roles" multiple class="form-control selectpicker show-tick" data-live-search="true" data-style="btn-primary">
                      @foreach ($roles as $role)
                      <option value="{{$role}}" {{ in_array($role,$userRole) ? 'selected' : '' }}>{{$role}}</option>
                      @endforeach
                    </select>

                    @if ($errors->has('roles'))
                    <span id="roles-error" class="error text-danger" for="roles">{{ $errors->first('roles') }}</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer ml-auto mr-auto">
              <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@push('js')
<script src="{{asset('assets/bootstrap-select/js/bootstrap-select.js') }}" referrerpolicy="origin"></script>
<script src="{{asset('assets/bootstrap-select/js/i18n/defaults-es_ES.min.js') }}" referrerpolicy="origin"></script>
<script>
  // To style all selects
  if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
    $('select').selectpicker('mobile');
    $('select').selectpicker('refresh');
  } else {
    $('select').selectpicker();
    $('select').selectpicker('refresh');
  }
</script>
@endpush
@endsection