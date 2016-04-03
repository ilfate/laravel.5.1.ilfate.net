
<div class="tooltip-spell-area"></div>
<div class="inventory-message-container"></div>
<div class="inventory-shadow"></div>
<div class="inventory">

    <div class="craft-spell-overlay"></div>
    <div class="items-filters-panel">
        @foreach($viewData['game']['item-types'] as $id => $type)
            <span class="items-filter name-{{$type['name']}} {{isset($type['class']) ? $type['class'] : ''}}"
                  data-name="{{$type['name']}}">
                <div class="svg svg-replace" data-svg="{{$type['icon']}}">
                    <svg class="svg-icon" viewBox="0 0 512 512">
                    </svg>
                </div>
            </span>
        @endforeach
    </div>


    <div class="items" data-items='{!! json_encode($viewData['game']['mage']['items'])!!}'>
    </div>
</div>

