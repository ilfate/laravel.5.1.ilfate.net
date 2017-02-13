

<div class="hex">
@foreach($hexMap as $hex)
    <div
        class="hexagon-container x_{{$hex->x}} y_{{$hex->y}}"
        style="margin-left: {{$hex->getXCoordinate(\Ilfate\ShipAi\Hex::SIDE_SIZE_REM_GALAXY_VIEW)}}rem;
                margin-top: {{$hex->getYCoordinate(\Ilfate\ShipAi\Hex::SIDE_SIZE_REM_GALAXY_VIEW)}}rem;"
        data-id="{{$hex->id}}"
        @click="openHex('{{$hex->id}}')"
    >
        <div class="hexagon"></div>
    </div>

@endforeach
</div>