@extends('layout.only-head')

@section('content')

<div class="games-page">
    <div class="landing-block td">
        <a href="{{ action('TdController@index') }}" class="rounded_block_link" data-target=".main-content-well">
            <i class="fa fa-th opacity-animation"></i>
            <span class="text bounce">TD</span>
        </a>
    </div>
    <div class="landing-block spell-craft">
        <div class="demo-link">
            <a href="/Spellcraft/savedLog?log=battle-with-witch">Demo1</a>
            <a href="/Spellcraft/savedLog?log=battle-with-spiders">Demo2</a>
        </div>
        <a href="{{ action('MageSurvivalController@index') }}" class="rounded_block_link" data-target=".main-content-well">
            <i class="fa fa-magic opacity-animation"></i>
            <span class="text bounce">Spellcraft</span>
        </a>
    </div>
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