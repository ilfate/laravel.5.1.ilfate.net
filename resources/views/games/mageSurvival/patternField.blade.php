
<div class="pattern-field">
    <?php $radius = $viewData['game']['config']['screenRadius']; ?>
    <?php $cellSize = $viewData['game']['config']['cellSize']; ?>
    @for($y = -$radius; $y <= $radius; $y++)
        @for($x = -$radius; $x <= $radius; $x++)
            <div class="pattern-cell x-{{$x}} y-{{$y}}" data-x="{{$x}}" data-y="{{$y}}" style="margin-left: {{($radius + $x) * $cellSize}}rem;margin-top: {{($radius + $y) * $cellSize}}rem;"></div>
        @endfor
    @endfor
</div>


