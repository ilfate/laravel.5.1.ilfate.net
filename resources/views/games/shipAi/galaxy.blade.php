@extends('games.shipAi.layout')

@section('content')

<div class="shipAi-main-wrapper">
    <div class="game-screen">
        <div id="galaxy-app">

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


@stop
