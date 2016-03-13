@extends('games.mageSurvival.layout')

@section('content')

<div class="game-container" >
    <input type="hidden" id="game-status" value="battle" />
    <div class="row game-inteface">
        <div class="middle-panel">

            <div class="craft-spell-overlay"></div>
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
            {{--<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">--}}
                {{--<div class="panel panel-default">--}}
                    {{--<div class="panel-heading" role="tab" id="headingItems">--}}
                        {{--<h4 class="panel-title">--}}
                            {{--<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseItems" aria-expanded="true" aria-controls="collapseItems">--}}
                                {{--Items--}}
                            {{--</a>--}}
                        {{--</h4>--}}
                    {{--</div>--}}
                    {{--<div id="collapseItems" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingItems">--}}
                        {{--<div class="panel-body">--}}
                            {{----}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="panel panel-default">--}}
                    {{--<div class="panel-heading" role="tab" id="headingTwo">--}}
                        {{--<h4 class="panel-title">--}}
                            {{--<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">--}}
                                {{--Spells--}}
                            {{--</a>--}}
                        {{--</h4>--}}
                    {{--</div>--}}
                    {{--<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">--}}
                        {{--<div class="panel-body">--}}
                            {{----}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="panel panel-default">--}}
                    {{--<div class="panel-heading" role="tab" id="headingThree">--}}
                        {{--<h4 class="panel-title">--}}
                            {{--<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">--}}
                                {{--Mage--}}
                            {{--</a>--}}
                        {{--</h4>--}}
                    {{--</div>--}}
                    {{--<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">--}}
                        {{--<div class="panel-body">--}}
                            {{----}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>
</div>
@stop
