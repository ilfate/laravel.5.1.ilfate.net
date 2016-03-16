@extends('games.mageSurvival.layout')

@section('content')
<div class="game-container" >
    <input type="hidden" id="game-status" value="battle" />
    <div class="row game-inteface">
        <div class="middle-panel">

            <div class="battle-border {{ $viewData['game']['world'] }}">
                <div class="mage-container"></div>
                @include('games.mageSurvival.patternField')
                <div class="battle-field current"></div>
                <div class="unit-field"></div>
            </div>
            <div class="tooltip-helper-area"></div>
            <script> mageSurvivalData = {!!json_encode($viewData['game'])!!} </script>
        </div>
        <div class="mage-info-panel">
            @include('games.mageSurvival.actions')
            @include('games.mageSurvival.mageProfile')
        </div>
        <div class="right-panel">
            <div class="tablet-button-panel">
                <a onclick="$('.items-col').toggle()"><i class="rpg-icon-small wpn_swd_normal"></i></a>
                <a onclick="$('.spells-col').toggle()"><i class="rpg-icon-small mgc_lightning_1"></i></a>
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
    </div>
</div>
@stop
