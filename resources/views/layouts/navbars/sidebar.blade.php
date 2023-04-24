<div class="sidebar" data-color="orange" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-3.jpg">
  <div class="logo">
    <a href="{{ (session()->get('parent')!='') ? route('users.change',(session()->get('parentId')!='') ?session()->get('parentId'):-1) : '#' }}" class="simple-text logo-normal">
      <img style="width:100px" src="{{ asset('material') }}/img/smartedu.png">
      <img style="width:100px" src="{{ asset('material') }}/img/jebp.png">
      <br>Bienvenido
      <br>
      <h6>
      @if (session()->get('parent')!='')
        {{session()->get('parent')}}
      @else
        {{Auth::user()->name}}
      @endif
      </h6>
      
    </a>
  </div>
  <!-- <div class="user">
                    <div class="photo">
                        <img src="../assets/img/default-avatar.png">
                    </div>
                    <div class="info ">
                        <a data-toggle="collapse" href="#collapseExample" class="" aria-expanded="true">
                            <span>Tania Andrew
                                <b class="caret"></b>
                            </span>
                        </a>
                        <div class="collapse show" id="collapseExample" style="">
                            <ul class="nav">
                                <li>
                                    <a class="profile-dropdown" href="#pablo">
                                        <span class="sidebar-mini">MP</span>
                                        <span class="sidebar-normal">My Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="profile-dropdown" href="#pablo">
                                        <span class="sidebar-mini">EP</span>
                                        <span class="sidebar-normal">Edit Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="profile-dropdown" href="#pablo">
                                        <span class="sidebar-mini">S</span>
                                        <span class="sidebar-normal">Settings</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> -->

  <div class="sidebar-wrapper">
    <ul class="nav">
        @if (session()->get('parent')!='')
        <li class="">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="material-icons"></i>
          <p>{{Auth::user()->name}}</p>
        </a>
       </li>
      @endif  
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="material-icons">dashboard</i>
          <p>{{ __('Inicio') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'attendance.myqr' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('attendance.myqr') }}">
          <i class="material-icons">blur_on</i>
          <p>{{ __('Mi QR') }}</p>
        </a>
      </li>
      @can('users.index')
      <li class="nav-item{{ $activePage == 'user-management' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('user.index') }}">
          <i class="material-icons">supervised_user_circle</i>
          <span class="sidebar-normal"> {{ __('User Management') }} </span>
        </a>
      </li>
      @endcan
      @can('roles.index')
      <li class="nav-item{{ $activePage == 'role-management' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('roles.index') }}">
          <i class="material-icons">people_alt</i>
          <p>{{ __('Roles') }}</p>
        </a>
      </li>
      @endcan
      @canany(['statements.index','statements.show'])
      <li class="nav-item{{ ($activePage == 'statements_inbox' or $activePage == 'statements_send' ) ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#statement-management" aria-expanded="{{ ($activePage == 'statements_inbox' or $activePage == 'statements_send' ) ? 'true' : 'false' }}">
          <i class="material-icons">content_paste</i>
          <p> {{ __('Statements') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse {{ ($activePage == 'statements_inbox' or $activePage == 'statements_send' ) ? ' show' : '' }}" id="statement-management">
          <ul class="nav">
            @can('statements.index')
            <li class="nav-item{{ $activePage == 'statements_inbox' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('statements.index','statements_inbox') }}">
                <span class="sidebar-mini"> IN </span>
                <span class="sidebar-normal">{{ __('Recibidos') }} </span>
              </a>
            </li>
            @endcan
            @can('statements.show')
            <li class="nav-item{{ $activePage == 'statements_send' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('statements.index','statements_send') }}">
                <span class="sidebar-mini"> OU </span>
                <span class="sidebar-normal"> {{ __('Emitidos') }} </span>
              </a>
            </li>
            @endcan
          </ul>
        </div>
      </li>
      @endcanany
      @canany(['delays.index','delays.show'])
      <li class="nav-item{{ ($activePage == 'delays_inbox' or $activePage == 'delays_send' ) ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#delays-management" aria-expanded="{{ ($activePage == 'delays_inbox' or $activePage == 'delays_send' ) ? 'true' : 'false' }}">
          <i class="material-icons">alarm_on</i>
          <p> {{ __('Delays') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse {{ ($activePage == 'delays_inbox' or $activePage == 'delays_send' ) ? ' show' : '' }}" id="delays-management">
          <ul class="nav">
            @can('delays.index')
            <li class="nav-item{{ $activePage == 'delays_inbox' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('delays.index','delays_inbox') }}">
                <span class="sidebar-mini"> IN </span>
                <span class="sidebar-normal">{{ __('Recibidos') }} </span>
              </a>
            </li>
            @endcan
            @can('delays.show')
            <li class="nav-item{{ $activePage == 'delays_send' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('delays.index','delays_send') }}">
                <span class="sidebar-mini"> OU </span>
                <span class="sidebar-normal"> {{ __('Emitidos') }} </span>
              </a>
            </li>
            @endcan
          </ul>
        </div>
      </li>
      @endcanany
      @can('attendance.index')
      <li class="nav-item{{ ($activePage == 'attendance.create' or $activePage == 'attendance.send' or $activePage == 'attendance.delays' or $activePage == 'attendance.scan') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#attendance-management" aria-expanded="{{ ($activePage == 'attendance.create' or $activePage == 'attendance.send' or $activePage == 'attendance.delays' or $activePage == 'attendance.scan') ? 'true' : 'false' }}">
          <i class="material-icons">ballot</i>
          <p> {{ __('Attendance') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse{{ ($activePage == 'attendance.create' or $activePage == 'attendance.export' or $activePage == 'attendance.send' or $activePage == 'attendance.delays' or $activePage == 'attendance.scan' ) ? ' show' : '' }}" id="attendance-management">
          <ul class="nav">
          
            @can('attendance.scan')
            <li class="nav-item{{ $activePage == 'attendance.scan' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('attendance.scan') }}">
                <span class="sidebar-mini"> SC </span>
                <span class="sidebar-normal">{{ __('Scan Attendance') }} </span>
              </a>
            </li>
            @endcan
            @can('attendance.create')
            <li class="nav-item{{ $activePage == 'attendance.create' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('attendance.register') }}">
                <span class="sidebar-mini"> RA </span>
                <span class="sidebar-normal">{{ __('Add Attendance') }} </span>
              </a>
            </li>
            @endcan
            <li class="nav-item{{ $activePage == 'attendance.delays' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('attendance.delays') }}">
                <span class="sidebar-mini"> DE </span>
                <span class="sidebar-normal"> {{ __('Ver Registro') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'attendance.export' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('attendance.export') }}">
                <span class="sidebar-mini"> DE </span>
                <span class="sidebar-normal"> {{ __('Exportar Registro') }} </span>
              </a>
            </li>
            @can('attendance.send')
            <li class="nav-item{{ $activePage == 'attendance.send' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('attendance.send') }}">
                <span class="sidebar-mini"> AS </span>
                <span class="sidebar-normal"> {{ __('Send Attendance') }} </span>
              </a>
            </li>
            @endcan
          </ul>
        </div>
      </li>
      @endcan
      @can('familyschoolsss.index')
      <li class="nav-item{{ ($activePage == 'familyschools.create' or $activePage == 'familyschools.send' or $activePage == 'familyschools.delays' or $activePage == 'familyschools.scan') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#familyschools-management" aria-expanded="{{ ($activePage == 'familyschools.create' or $activePage == 'familyschools.send' or $activePage == 'familyschools.delays' or $activePage == 'familyschools.scan') ? 'true' : 'false' }}">
          <i class="material-icons">ballot</i>
          <p> {{ __('Family Schools') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse{{ ($activePage == 'familyschools.create' or $activePage == 'familyschools.send' or $activePage == 'familyschools.delays' or $activePage == 'familyschools.scan' ) ? ' show' : '' }}" id="familyschools-management">
          <ul class="nav">
          <li class="nav-item{{ $activePage == 'familyschools.create' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('familyschools.create') }}">
                <span class="sidebar-mini"> RA </span>
                <span class="sidebar-normal">{{ __('Add Family Schools') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'familyschools.scan' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('familyschools.scan') }}">
                <span class="sidebar-mini"> SC </span>
                <span class="sidebar-normal">{{ __('Scan Family Schools') }} </span>
              </a>
            </li>
          </ul>
        </div>
      </li>
      @endcan
      @can('nothing')
      <li class="nav-item{{ $activePage == 'icons' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('icons') }}">
          <i class="material-icons">bubble_chart</i>
          <p>{{ __('Icons') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'notifications' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('notifications') }}">
          <i class="material-icons">notifications</i>
          <p>{{ __('Notifications') }}</p>
        </a>
      </li>
      @endcan
    </ul>
  </div>
</div>