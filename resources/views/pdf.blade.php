<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8" />
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ __('Agenda JEBP - SMART ELECTRONICS') }}</title>
  @laravelPWA
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('material') }}/img/apple-icon.png">
  <link rel="icon" type="image/png" href="{{ asset('material') }}/img/favicon.png">

  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css" />

  <!-- CSS Files -->
  <link href="{{ asset('material') }}/css/material-dashboard.css?v=2.1.1" rel="stylesheet" />

  @stack('head_scripts')



  <!-- <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'> -->
  <!--<link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet' /> -->


</head>

<body>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card ">
            <div class="card-body" id="studentList">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <td class="toprint text-center"><b>QR Left</b></td>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($users as $user)
                  <tr>
                    <td>
                      <div class="row wrap">
                        <!-- <label class="col-sm-2 col-form-label">{{ __('QR de Usuarios') }}</label> -->
                        <div class="col-sm-12">
                          <div class="form-group">
                            <div id="toprint_{{$user->id}}" class="toprint text-center">
                              <div>Colegio Jesús el Buen Pastor</div>
                              <div>{{$user->name}}</div>
                              <figure>
                                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->color(0, 0, 128)->errorCorrection('H')->merge('https://agenda.jebp.smarteduperu.com/material/img/jebp.png', .2, true)->size(350)->margin(0)->generate($user->username)) !!}">
                                <figcaption>{{$user->username}}</figcaption>
                              </figure>
                            </div>
                          </div>
                        </div>
                      </div>
                    </td> 
                  </tr>
		  @endforeach
                  </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
