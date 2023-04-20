@extends('layouts.app', ['class' => 'off-canvas-sidebar', 'activePage' => 'home', 'title' => __('Agenda')])

@section('content')
<div class="container" style="height: auto;">
  <div class="row justify-content-center">
      <div class="col-lg-7 col-md-8">
          <h1 class="text-white text-center">{{ __('Bienvenido a la agenda digital del Colegio Jesús el Buen Pastor') }}</h1>
      </div>
      <div class="col-lg-6 col-md-7">
      <div class="text-center">
      <img class="img-fluid" alt="Responsive image" src="{{ asset('material') }}/img/smartedu.png">
      </div>
      </div>
      <div class="col-lg-6 col-md-7">
      <div class="text-center">
      <img class="img-fluid" alt="Responsive image" src="{{ asset('material') }}/img/jebp.png">
      </div>
      </div>
  </div>
</div>
@endsection
