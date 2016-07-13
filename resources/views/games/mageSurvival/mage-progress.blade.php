@extends('games.mageSurvival.layout')

@section('content')
    <div class="page-controlls">
        <a href="/Spellcraft">Back to game</a>
    </div>
    <div class="player-mage-list">
        @if(count($viewData['mages']))
            @foreach($viewData['mages'] as $mage)
                <div class="dead-mage">
                    <div class="dead-mage-eye left-eye svg svg-replace" data-svg="icon-dead-eye" data-color="brown">
                        <svg class="svg-icon " viewBox="0 0 512 512">
                        </svg>
                    </div>
                    <div class="dead-mage-eye right-eye svg svg-replace" data-svg="icon-dead-eye" data-color="brown">
                        <svg class="svg-icon " viewBox="0 0 512 512">
                        </svg>
                    </div>
                    <div class="text">
                        <span class="name">{{$mage['name']}}</span>
                        @if ($mage['status'] == 2) R.I.P.@else <small>(points are added when mage dies)</small> @endif<br>
                        <button type="button" class="open-dead-info btn btn-lg">
                            <i class="fa fa-arrow-down" aria-hidden="true"></i>
                        </button>
                    </div>

                </div>
                <div class="hidden-mage-info">
                    <table class="table">
                        <caption>{{$mage['name']}}`s adventures earned you next points:</caption>
                        <thead>
                        <tr>
                            <th>School</th>
                            <th>Points earned by {{$mage['name']}}</th>
                            <th>Your total points</th>
                        </tr>

                        </thead>
                        <tbody>
{{--                            @foreach(\Ilfate\MageSurvival\Spell::$energyToStats as $enegry => $statName)--}}
                            @foreach($viewData['schools'] as $schoolId => $schoolConfig)
                                @if (!empty($mage['stats'][\Ilfate\MageSurvival\Spell::$energyToStats[$schoolConfig['name']]]))
                                <tr>
                                    <td>
                                        <div class="svg svg-replace" data-svg="{{$schoolConfig['icon']}}" data-color="{{$schoolConfig['color']}}">
                                            <svg class="svg-icon " viewBox="0 0 512 512">
                                            </svg>
                                        </div>
                                    </td>
                                    <td>{{$mage['stats'][\Ilfate\MageSurvival\Spell::$energyToStats[$schoolConfig['name']]]}}</td>
                                    <td>{{$viewData['stats'][$schoolConfig['name']]}}</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        @endif
        <div class="mage">

            {{--<div id="create-mage-pop-up">--}}
                {{--<form action="/Spellcraft/createMage" method="POST" >--}}
                    @foreach($viewData['mages-types'] as $key => $typeData)
                        @if(!empty($typeData['available']))
                            <div class="single-mage-type" >
                                <a class="mage-type-select" data-type="{{$typeData['name']}}">
                                    <div class="svg svg-replace" data-svg="{{$typeData['icon']}}">
                                        <svg class="svg-icon " viewBox="0 0 512 512">
                                        </svg>
                                    </div>
                                    <span class="mage-type-name">{{$typeData['name']}}</span>
                                </a>
                            </div>
                        @else
                            <div class="single-mage-type locked">
                                <a class="locked svg svg-replace" data-svg="icon-padlock">
                                    <svg class="svg-icon " viewBox="0 0 512 512">
                                    </svg>

                                </a>
                                <span class="mage-type-name"><span class="crossed">{{$typeData['name']}}</span>Locked</span>
                                <div class="requirements">
                                    @if (!empty($typeData['stats']))
                                        @foreach($typeData['stats'] as $stat => $value)
                                            <?php if (empty($viewData['stats'][$stat])) { $viewData['stats'][$stat] = 0; }
                                            $progress = $viewData['stats'][$stat] / $value * 100;
                                            $progress = $progress > 100 ? 100 : $progress;
                                            $progress = $progress < 8 ? 8 : $progress;
                                            ?>
                                            <?php $schoolId = \Ilfate\MageSurvival\Spell::$schoolNameToId[$stat];
                                                $colorName = $viewData['schools'][$schoolId]['color'];
                                            ?>
                                            <div class="progress">
                                                <div class="progress-bar colored" data-color="{{$colorName}}" role="progressbar" aria-valuenow="{{$progress}}" aria-valuemin="10%" aria-valuemax="100" style="width: {{$progress}}%;">
                                                    {{$stat}}: {{$viewData['stats'][$stat]}} / {{$value}}
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                {{--</form>--}}
            {{--</div>--}}
        </div>
    </div>






    <input type="hidden" id="game-status" value="mage-list" />
@stop
