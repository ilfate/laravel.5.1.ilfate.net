@extends('layout.empty')

@section('content')

    {{--<div class="games-page">--}}
        {{--<div class="block-container">--}}
            {{--<div class="series game-block">--}}
                {{--<a href="{{ action('GuessGameController@index') }}">--}}
                    {{--<img src="/images/game/GuessSeriesLowQuality.jpg" />--}}
                {{--</a>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="block-container">--}}
            {{--<div class="math-effect game-block">--}}
                {{--<a href="{{ action('MathEffectController@index') }}">--}}
                    {{--<img src="/images/game/tdTitle-1011.jpg" />--}}
                {{--</a>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

<div class="games-page">
    <div class="landing-block guess-series">
        <a href="{{ action('GuessGameController@index') }}" class="rounded_block_link" data-target=".main-content-well">
            <i class="fa fa-film opacity-animation"></i>
            <span class="text">Guess Series</span>
        </a>
    </div>
    <div class="landing-block math-effect">
        <a href="{{ action('MathEffectController@index') }}" class="rounded_block_link" data-target=".main-content-well">
            <span class="text">Math Effect</span>
        </a>
    </div>
</div>



@stop