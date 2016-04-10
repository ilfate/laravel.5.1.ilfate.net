@extends('games.mageSurvival.layout')

@section('content')

@include('games.mageSurvival.overlay-load')
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
                <div class="dialog-field"></div>


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
            <a class="toggle-chat">
                <div class="svg svg-replace" data-svg="icon-conversation">
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
    <div class="clearfix"></div>
    <div class="bottom-panel" id="bottom-panel">
        <div class="last-message" id="last-message"><div class="cover">
                <div class="left">
                    <div class="svg svg-replace color-white rotate-180" data-svg="icon-fall-down">
                        <svg class="svg-icon" viewBox="0 0 512 512">
                        </svg>
                    </div>
                </div>
                <div class="middle"></div>
                <div class="right">
                    <div class="svg svg-replace color-white rotate-180" data-svg="icon-fall-down">
                        <svg class="svg-icon" viewBox="0 0 512 512">
                        </svg>
                    </div>
                </div>
            </div><div class="content"></div></div>
        <div class="chat-container"></div>
    </div>

</div>
@stop
