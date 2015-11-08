@extends('layout.empty')

@section('content')

<div class="cosmos">
    @include('games.cosmos.ship.main', ['ship' => $ship])
</div>


    @include('games.cosmos.templates')
@stop

