@extends('games.td.layout')

@section('content')

    <div class="menu">
        <div class="button-section">
            <a class="btn td-button" href="/games" >Back to games list</a>
        </div>
        <div class="button-section">
            <button class="btn td-button pause-button" >Pause</button>
        </div>
        <div class="wave-indicator">
            Wave 1 (1HP)
        </div>
    </div>
    <div class="clearfix"></div>

<div id="td-start"></div>
<div class="selection-zone">
    <div class="towers-list">

    </div>
</div>

@stop
