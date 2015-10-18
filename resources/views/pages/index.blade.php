@extends('layout.landing.layout')

@section('content')

{{--<div class="page-header">--}}
    {{--<h1>Hello! My name is Ilya Rubinchik <small>and this is my website!</small></h1>--}}
{{--</div>--}}

<div class="landing-page-container">
    <div class="main-page-block cv">
        <a href="{{ action('PageController@cv') }}" class="rounded_block_link">
            <div class="rounded_block" >
                <span class="text">Cv</span>
    {{--         @include('interface.button-block', array('text' => 'CV', 'background' => '/images/my/code1_s.jpg'))--}}
            </div>
        </a>
    </div>
    <div class="main-page-block code">
        <a href="{{ action('CodeController@index') }}" class="rounded_block_link">
            <div class="rounded_block" >
                <span class="text">Code</span>
            </div>
        </a>

    </div>
    <div class="main-page-block games">
        <a href="{{ action('GamesController@index') }}" class="rounded_block_link" data-target=".main-content-well">
            <div class="rounded_block" >
                <span class="text">Games</span>

            </div>
    {{--         @include('interface.button-block', array('text' => 'Game', 'background' => '/images/game/tank1_s.jpg'))--}}
        </a>
    </div>
</div>

@stop