@extends('games.mageSurvival.layout')

@section('content')

    <div class="player-mage-list">
        <div class="auth-bar">
            @if($viewData['user']->is_guest)
                <span>You new here? If not you can login <a href="/login?page=spellcraft">here</a></span>
            @else
                <h3>Hello@if ($viewData['user']->name) {{ ' ' . $viewData['user']->name }} @endif! Welcome back! </h3>
            @endif
        </div>
        @if($viewData['mages']->count())
            @foreach($viewData['mages'] as $mage)
                <div class="mage">{{$mage->name}}</div>
            @endforeach
        @else
        <div class="mage">
            You have no mages yet.
        </div>
        @endif
        <div class="mage">

            <a id="mage-create-button">Create new mage</a>
            <div id="create-mage-pop-up">
                <form action="/Spellcraft/createMage" method="POST" >
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
                            {{--<div><a class="locked">Locked</a></div>--}}
                        @endif
                    @endforeach
                        <div class="single-mage-type">
                            <a class="locked svg svg-replace" data-svg="icon-padlock">
                                <svg class="svg-icon " viewBox="0 0 512 512">
                                </svg>

                            </a>
                            <span class="mage-type-name">Locked</span>
                        </div>
                        <div class="last-step">
                            <input type="text" class="mage-name" placeholder="Name" />
                            <input type="hidden" class="mage-type" value="" />
                            <a class="btn submit">Create</a>
                        </div>
                </form>
            </div>
        </div>
    </div>






    <input type="hidden" id="game-status" value="mage-list" />
@stop
