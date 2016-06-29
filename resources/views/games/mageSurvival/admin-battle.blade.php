@extends('games.mageSurvival.layout')

@section('content')

@include('games.mageSurvival.overlay-load')

<div class="admin-view">

    @include('games.mageSurvival.battle')

</div>
<input type="hidden" id="game-status" value="admin-battle" />


@stop
