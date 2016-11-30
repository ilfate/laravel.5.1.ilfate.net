@extends('games.td.layout')

@section('content')

    <div class="menu">
        <div class="button-section">
            <button class="btn td-button back-to-games-list-button" >Back to games list</button>
        </div>
        <div class="button-section">
            <button style="opacity: 0;" class="btn td-button pause-button" >Pause</button>
        </div>

        <div class="wave-status" style="opacity: 0;">
            <div class="wave-indicator next">
                Next:<span class="next-description">Boss 3HP</span>
            </div>
            <div class="wave-indicator current">
                Wave 1 (1HP)
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
<div class="start-overlay">
    <div class="button-container">
        <button class="start-game-button">Start game</button>
    </div>
    <div class="button-container load" style="display: none;">
        <button class="load-game-button">Load last game</button>
    </div>
    <div class="button-container">
        <button class="about-the-game-button">About the game</button>
    </div>
</div>
<div style="opacity: 0;" id="td-start"></div>
<div class="selection-zone" style="opacity: 0;">
    <div class="upgrade-container">
        <span>Upgrade selected tower</span>
        <button></button>
    </div>
    <div class="towers-list">

    </div>
</div>

@stop
