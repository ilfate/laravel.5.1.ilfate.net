@extends('layout.head')

@section('layout')

@include('menu')

<div class="container main">
    <div class="main-content-well well well-small ">
        @yield('content')
    </div>
</div>

@yield('after-content')

<input type="hidden" name="_token" id="laravel-token" value="{{ csrf_token() }}">

@stop

