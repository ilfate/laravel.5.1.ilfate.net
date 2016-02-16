@extends('games.mageSurvival.layout')

@section('content')

Battle

<div class="game-container" >
    <input type="hidden" id="game-status" value="battle" />
    <div class="row game-inteface">
        <div class="col-md-2">left</div>
        <div class="col-md-8">
            <div class="battle-field {{ $viewData['game']['world'] }}">

            </div>
            <script> mageSurvivalData = {!!json_encode($viewData['game'])!!} </script>
        </div>
        <div class="col-md-2">right

            <a id="forward-button">Forward</a>
        </div>
    </div>
</div>
@stop
