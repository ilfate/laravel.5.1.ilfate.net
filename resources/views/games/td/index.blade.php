@extends('games.td.layout')

@section('content')

    <div class="menu">
        <div class="hidden-menu">
            <div class="button-section">
                <button class="btn td-button restart-button" >Restart game</button>
            </div>
            <div class="clearfix"></div>
            <div class="button-section">
                <button class="btn td-button save-game-button" >Save game</button>
            </div>
            <div class="clearfix"></div>
            <div class="button-section">
                <button class="btn td-button speed-up-button" >Speed up</button>
            </div>
            <div class="clearfix"></div>
            <div class="button-section">
                <button class="btn td-button how-to-play-button" >How to play</button>
            </div>
            <div class="clearfix"></div>
            <div class="button-section">
                <button class="btn td-button open-stats-button" >Leaderboards</button>
            </div>
        </div>
        <div class="hambuger-button">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </div>
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

    <div class="start" @if($restart) style="display: none" @endif>
        <div class="button-container">
            <button class="start-game-button">Start game</button>
        </div>
        <div class="button-container load" style="display: none;">
            <button class="load-game-button">Load last game</button>
        </div>
        <div class="button-container">
            <button class="how-to-play-button">How to play</button>
        </div>
        <div class="button-container">
            <button class="test-button">Test</button>
        </div>
    </div>
    <div class="end" style="display: none">
        <div class="good">
            <div class="page-header">
                <h1>You survived <span class="waves-survived-number"></span> waves!<small> try better?</small></h1>
            </div>
        </div>
        <div class="bad" style="display: none;">
            <div class="page-header">
                <h1>You did good. <small>But you can do better!</small></h1>
            </div>
            <p>Want to learn how to play? <a class="how-to-play-button">Click here</a></p>
        </div>
        <br>
        <div class="good stats" style="display:none;">
            <p>That places you are on the <span class="your-standing-number"></span> position today!
                <br><a class="open-stats-button">See more stats</a></p>
        </div>
        @if(empty($userName))
            <form id="TDNameForm" class="ajax result-text" method="post" action="{{ action('TdController@saveName') }}">
                <input type="text" name="name" />
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="checkKey" id="checkKey" value="{{ str_random(10) }}" />

                <button class="btn btn-primary" type="submit">Save my name</button>
            </form>
        @endif
        <br>
        <p>
            Click <a class="restart-button">HERE</a> to try again! Or try my other <a href="/games">GAMES</a>
        </p>

        <div class="bottom-button-container">
            <button style="float:left;" class="btn btn-warning back-to-games-list-button">Other games</button>
            <button style="float:right;" class="btn btn-success restart-button">Restart</button>
        </div>
    </div>
</div>
<div style="opacity: 0;" id="td-start"></div>
<div class="selection-zone" style="opacity: 0;">
    <div class="upgrade-container">
        <button class="badge destroy-tower-button">destroy</button>
        <span>Upgrade selected tower</span>
    </div>
    <div class="towers-list">

    </div>
    <div class="clearfix"></div>
    <div class="tower-legend">
        <div class="row">
            <div class="description-cell-center green"></div><span class="text"> - attacks all targets</span>
        </div>
        <div class="row">
            <div class="description-cell-center blue"></div><span class="text"> - attacks just one target in reach</span>
        </div>
        <div class="row">
            <div class="description-cell-center gold"></div><span class="text"> - attacks once in two turns</span>
        </div>
    </div>
</div>

    <input type="hidden" id="userName" value="{{$userName}}" />

@stop
