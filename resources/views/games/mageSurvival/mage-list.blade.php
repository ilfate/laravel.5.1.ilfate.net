@extends('games.mageSurvival.layout')

@section('content')

Mage list

    <a id="mage-create-button">Create new mage</a>

    <div id="create-mage-pop-up">
        <div >
            @foreach($viewData['mages-types'] as $key => $typeData)
                @if(!empty($typeData['available']))
                    <div class="single-mage-type">
                        <a class="mage-type">{{$typeData['name']}}</a>
                        <input type="text" class="mage-name" placeholder="Name" />
                        <input type="hidden" class="mage-type" value="{{$key}}" />
                        <a class="btn submit">Create</a>
                    </div>
                @else
                    <div><a class="locked">Locked</a></div>
                @endif
            @endforeach
        </div>
    </div>

    <input type="hidden" id="game-status" value="mage-list" />
@stop
