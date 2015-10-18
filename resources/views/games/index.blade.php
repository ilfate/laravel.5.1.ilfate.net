@extends('layout.empty')

@section('content')

    <div class="games-page">
        <div class="block-container">
            <div class="series game-block">
                <a href="{{ action('GuessGameController@index') }}">
                    <img src="/images/game/GuessSeriesLowQuality.jpg" />
                </a>
            </div>
        </div>
        <div class="block-container">
            <div class="math-effect game-block">
                <a href="{{ action('MathEffectController@index') }}">
                    <img src="/images/game/tdTitle-1011.jpg" />
                </a>
            </div>
        </div>
    </div>



@stop