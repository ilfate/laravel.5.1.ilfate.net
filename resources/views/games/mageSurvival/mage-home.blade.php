@extends('games.mageSurvival.layout')

@section('content')
    <div class="game-container" >
        <input type="hidden" id="game-status" value="mage-home" />


        <div class="mage-info-panel">
            @include('games.mageSurvival.mageProfile')
        </div>
        <div class="right-panel">
            <div id="mobile-spell-info-container"></div>
            <div class="row spells-items-row">
                <div class="col-md-6 items-col">
                    @include('games.mageSurvival.items')
                </div>
                <div class="col-md-6 spells-col">
                    @include('games.mageSurvival.spells')
                </div>
            </div>
        </div>
    </div>
@stop
