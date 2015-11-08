<?php
/**
 * @var \Ilfate\Cosmos\Ship\Ship $ship
 */

?>


<div class="ship-container normal-size"
     style="
             width:{{$ship->getViewAttribute(\Ilfate\Cosmos\Ship\Ship::VIEW_ATTRIBUTE_SHIP_WIDTH)}}em;
             height:{{$ship->getViewAttribute(\Ilfate\Cosmos\Ship\Ship::VIEW_ATTRIBUTE_SHIP_HEIGHT)}}em;
             padding-left:{{-$ship->getViewAttribute(\Ilfate\Cosmos\Ship\Ship::VIEW_ATTRIBUTE_SHIP_MIN_X)}}em;
             padding-top:{{-$ship->getViewAttribute(\Ilfate\Cosmos\Ship\Ship::VIEW_ATTRIBUTE_SHIP_MIN_Y)}}em;
             ">
    <script>
        var shipCells = [];

        $(document).ready(function() {
                        Cosmos.Game.createShipFromJson({!! $ship->exportAsJson() !!});
                        Cosmos.Game.renderShip();
                    });
    </script>
</div>