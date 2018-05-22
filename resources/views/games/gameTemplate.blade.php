@extends('layout.only-head')

@section('content')

<div class="overflow-hidden">
<div class="game-template-container">
    <div class="container main">
        <div class="main-content-well well well-small ">

            <canvas id="demoCanvas" width="576" height="576">
                alternate content
            </canvas>

            <div class="rules">
                <h3>Controls</h3>
                <strong>W,A,S,D</strong> - move<br>
                <strong>E</strong> - destroy wall<br>
                <br>
                <h3>Info</h3>
                This is not actually a game. This is just result of my experiments during studing new Canvas framework (Createjs)
                <br>
                <br>
                This was done in 2011... very old stuff...
            </div>

        </div>
    </div>
</div>
</div>

@include('blocks.gdpr')

@stop
