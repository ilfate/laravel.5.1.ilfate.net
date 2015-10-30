@extends('layout.empty')

@section('content')

<div class="games-page">
    <div class="landing-block guess-series">
        <a href="{{ action('GuessGameController@index') }}" class="rounded_block_link" data-target=".main-content-well">
            <i class="fa fa-film opacity-animation"></i>
            <span class="text bounce">Guess Series</span>
        </a>
    </div>
    <div class="landing-block math-effect">
        <a href="{{ action('MathEffectController@index') }}" class="rounded_block_link" data-target=".main-content-well">
            <i class="fa fa-th-large opacity-animation"></i>
            <span class="text bounce">Math Effect</span>
        </a>
    </div>
</div>



@stop