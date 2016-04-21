@extends('games.mageSurvival.layout')

@section('content')
    @include('games.mageSurvival.overlay-load')
    <div class="game-container home" >
        <input type="hidden" id="game-status" value="mage-home" />

        <div class="home-portal-animation">

            <div class="svg svg-replace svg-portal inside" data-svg="icon-magic-portal-inside">
                <svg class="svg-icon" viewBox="0 0 512 512">
                </svg>
            </div>
            <div class="animation-layer"></div>
            <div class="svg svg-replace svg-portal main" data-svg="icon-magic-portal">
                <svg class="svg-icon" viewBox="0 0 512 512">
                </svg>
            </div>
            <div class="svg svg-replace svg-portal border" data-svg="icon-magic-portal-border">
                <svg class="svg-icon" viewBox="0 0 512 512">
                </svg>
            </div>
        </div>

        <h1 class="home-h1">
           Here you can choose a world
        </h1>
        <div class="worlds-container">
            @foreach($viewData['game']['available-worlds'] as $availableWorldId)
                <a class="world" href="/Spellcraft/world/{{$viewData['game']['worlds'][$availableWorldId]['map-type']}}">
                    {{$viewData['game']['worlds'][$availableWorldId]['name']}}
                </a>
            @endforeach

        </div>

    </div>
@stop
