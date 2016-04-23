@extends('games.mageSurvival.layout')

@section('content')

    <div class="buttons-right">
        <a class="export">EXP</a>
        <a class="go-top">top</a>
        <a class="go-left">left</a>
        <a class="go-right">right</a>
        <a class="go-bottom">down</a>
    </div>
    <input type="hidden" value="{{$mapName}}" id="map-name" />
<div class="map-builder">
    <div class="map battle-border"></div>
</div>
    @if(!empty($mapValue))
    <script>
        mapBuilderValue = {!! $mapValue !!};
        mapBuilderOffsetX = {!! $offsetX !!};
        mapBuilderOffsetY = {!! $offsetY !!};
    </script>
    @endif

@stop
