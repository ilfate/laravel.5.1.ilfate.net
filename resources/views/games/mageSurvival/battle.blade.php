@extends('games.mageSurvival.layout')

@section('content')
<div class="game-container" >
    <input type="hidden" id="game-status" value="battle" />

    <div class="middle-panel" id="mage-middle-panel">
        <div class="responsive-container">
            <div class="battle-border {{ $viewData['game']['world'] }}">
                <div class="mage-container" id="mage-container"></div>
                @include('games.mageSurvival.patternField')
                <div class="battle-field current"></div>
                <div class="unit-field"></div>

            </div>
            <div class="tooltip-helper-area"></div>
            <script> mageSurvivalData = {!!json_encode($viewData['game'])!!} </script>
        </div>
    </div>
    <div class="mage-info-panel">
        @include('games.mageSurvival.mageProfile')
        @include('games.mageSurvival.actions')
    </div>
    <div class="right-panel">
        <div class="interface-switch-panel">
            <a class="toggle-inventory">
                <svg class="svg-icon svg-replace" viewBox="0 0 500 500" data-svg="icon-chest">
                </svg>
            </a>
            <a class="toggle-spellbook">
                <svg class="svg-icon svg-replace" viewBox="0 0 500 500" data-svg="icon-spell">
                </svg>
            </a>
            <a class="toggle-mage-info">
                <svg class="svg-icon svg-replace" viewBox="0 0 500 500" data-svg="icon-think">
                </svg>
            </a>
        </div>
        <div class="row spells-items-row">
            <div class="col-md-6 items-col">
                @include('games.mageSurvival.items')
            </div>
            <div class="col-md-6 spells-col">
                @include('games.mageSurvival.spells')
            </div>
        </div>
    </div>
    @include('games.mageSurvival.mobile-controls')
</div>
@stop
