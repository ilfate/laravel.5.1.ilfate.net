@extends('layout.empty')

@section('content')

    <?php
    /**
     * @var \Ilfate\Hex\HexagonalField $field
     */
    ?>

    <a class="btn" href="/hex/reset">Reset game</a><br>
<div class="hex">


    @foreach($field->getCells() as $y => $row)
        @foreach($row as $x => $cell)
            <div class="hexagon-container x_{{$cell->getX()}} y_{{$cell->getY()}}"
                 style="margin-left: {{$cell->getXCoordinate()}}em;margin-top: {{$cell->getYCoordinate()}}em;"
                 data-x="{{$cell->getX()}}"
                 data-y="{{$cell->getY()}}"
                    >
                <div class="hexagon-click-area"></div>
                <div class="hexagon {{$cell->getType()}}">
                    @if($cell->getType() == 'gun')
                        @foreach($cell->getGuns() as $gunDirection)
                        <div class="gun-container gun_{{$gunDirection}}">
                            <div class="gun " style="width:{{$cell->cellsToEm($cell->getLaserLength($gunDirection))}}">

                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>

            </div>
        @endforeach
    @endforeach
</div>

@stop

