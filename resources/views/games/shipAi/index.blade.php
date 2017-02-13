@extends('games.whiteHorde.layout')

@section('content')

<div class="shipAi-main-wrapper">
    <div class="game-screen">
        <div id="game-app">
            {!! $viewData !!}

            @if(!empty($hexMap))
                @include('games.shipAi.hexMap')
            @endif
        </div>
        <div class="clearfix"></div>
        <div id="chat-app">
            <div v-for="message in messages" class="message">
                @{{message.text}}
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="userName" value="{{$userName}}" />

@stop
