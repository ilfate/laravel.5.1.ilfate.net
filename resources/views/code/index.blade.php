@extends('layout.empty')

@section('content')

<div class="code-page">

    <div class="landing-block github">
        <a target="_blank" href="https://github.com/ilfate/laravel.5.1.ilfate.net" class="rounded_block_link" data-target=".main-content-well">
            <img src="/svg/github.svg" class="opacity-animation" />
            <span class="text">Github</span>
        </a>
    </div>
    <div class="landing-block game-template">
        <a href="{{ action('CodeController@gameTemplate') }}" class="rounded_block_link" data-target=".main-content-well">
            <span class="text">Game-template</span>
        </a>
    </div>
    <div class="landing-block robot-rock bounce">
        <a href="{{ action('CodeController@robotRock') }}" class="rounded_block_link" data-target=".main-content-well">
            <i class="fa fa-space-shuttle opacity-animation"></i>
            <span class="text">Robot Rock</span>
        </a>
    </div>

</div>

@stop
