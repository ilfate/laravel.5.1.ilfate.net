@extends('games.shipAi.layout')

@section('content')

    <div class="shipAi-main-wrapper">
        <div class="game-screen">
            <div id="hex-app">
                <div class="menu">
                    <md-button href="/shipAi/galaxy" class="md-primary">To galaxy view</md-button>
                </div>
                <div class="hex-for-page layer1" style="transform:scale(1.002)">
                    <div class="hexagon"></div>
                </div>
                <div class="hex-for-page layer2">
                    <div class="hexagon black"></div>
                </div>
                @foreach($hex->stars as $star)
                    <div class="star-container"
                    data-x="{{($star->x - ($hex->x * $width + $hex->y * $width / 2))}}"
                    data-y="{{($star->y - ($hex->y * \Ilfate\ShipAi\Hex::SIDE_SIZE_LIGHT_YEARS * 1.5))}}"
                         @click="starClick({{$star->id}})"
                    >
                        <div class="point"></div>
                        <div class="description">
                            {{$star->name ? ($star->name . ' ') : ''}}[{{$star->number}}]<br>
                            Star coordinate ({{$star->x}}, {{$star->y}})
                        </div>
                    </div>
                @endforeach

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