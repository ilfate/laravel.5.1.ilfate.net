@extends('layout.guess.head')

@section('layout')


<div class="guess-body">
    <div class="top-navigation">
        <a href="{{ @action('GamesController@index') }}">Back to games</a>
    </div>
    <div class="container main">
        <div class="row">
            <div class="col-md-9 game-area">
                @yield('content')
            </div>
            <div class="col-md-3 sidebar-col">
                @yield('sidebar')
            </div>
        </div>
    </div>
</div>


<input type="hidden" name="_token" id="laravel-token" value="{{ csrf_token() }}">



@stop

