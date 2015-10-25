@extends('layout.empty')

@section('content')

<div class="code-page">
    {{--<div class="container main">--}}
        {{--<div class="main-content-well well well-small ">--}}

            <div class="row show-grid github">
                <a href="https://github.com/ilfate/laravel.5.1.ilfate.net" class="rounded_block_link" data-target=".main-content-well">
                    @include('interface.button-block', array('text' => 'Github', 'background' => '/svg/github.svg'))
                </a>
            </div>
            <div class="row show-grid game-template">
                <a href="{{ action('CodeController@gameTemplate') }}" class="rounded_block_link" data-target=".main-content-well">
                    @include('interface.button-block', array('text' => 'Game-template'))
                </a>
            </div>
            <div class="row show-grid robot-rock">
                <a href="{{ action('CodeController@robotRock') }}" class="rounded_block_link" data-target=".main-content-well">
                    <div class="rounded_block" >
                        <i class="fa fa-space-shuttle opacity-animation"></i>
                        <span class="text">Robot Rock</span>
                    </div>
                </a>
            </div>

        {{--</div>--}}
    {{--</div>--}}
</div>

@stop
