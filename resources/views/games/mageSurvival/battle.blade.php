@extends('games.mageSurvival.layout')

@section('content')
<div class="game-container" >
    <input type="hidden" id="game-status" value="battle" />

    <div class="middle-panel" id="mage-middle-panel">
        <div class="responsive-container">
            <div class="battle-border {{ $viewData['game']['world'] }}" id="battle-border">
                <div class="mage-container" id="mage-container"></div>
                @include('games.mageSurvival.patternField')
                <div class="battle-field current"></div>
                <div class="unit-field"></div>
                <div class="animation-field"></div>

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
                <div class="svg svg-replace" data-svg="icon-chest">
                    <svg class="svg-icon" viewBox="0 0 512 512" >
                    </svg>
                </div>
            </a>
            <a class="toggle-spellbook">
                <div class="svg svg-replace" data-svg="icon-spell">
                    <svg class="svg-icon" viewBox="0 0 512 512">
                    </svg>
                </div>
            </a>
            <a class="toggle-mage-info">
                <div class="svg svg-replace" data-svg="icon-think">
                    <svg class="svg-icon" viewBox="0 0 512 512">
                    </svg>
                </div>
            </a>
        </div>
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
    @include('games.mageSurvival.mobile-controls')
</div>
@stop
