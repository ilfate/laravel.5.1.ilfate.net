@extends('layout.head')

@section('layout')


@yield('content')

<input type="hidden" name="_token" id="laravel-token" value="{{ csrf_token() }}">

@stop

