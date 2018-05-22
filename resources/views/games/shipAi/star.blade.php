@extends('games.shipAi.layout')

@section('content')

    <div class="shipAi-main-wrapper">
        <div class="game-screen">
            <script>
                starSystem = {!! json_encode($star->export()) !!};
            </script>
            <div id="star-canvas-container"></div>
            <div id="star-app">
                <div class="menu">
                    <md-button href="/shipAi/hex/{{$star->hex_id}}" class="md-primary">To hex view</md-button>
                </div>




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