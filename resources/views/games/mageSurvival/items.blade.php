
<div class="tooltip-spell-area"></div>
<div class="inventory-message-container"></div>
<div class="inventory">

    <div class="craft-spell-overlay"></div>
    <div class="items-filters-panel">
        @foreach($viewData['game']['item-types'] as $id => $type)
            <span class="items-filter name-{{$type['name']}} {{isset($type['class']) ? $type['class'] : ''}}"
                  data-name="{{$type['name']}}">
                <svg class="svg-icon svg-replace" viewBox="0 0 500 500" data-svg="{{$type['icon']}}">
                </svg>
            </span>
        @endforeach
    </div>


    <div class="items" data-items='{!! json_encode($viewData['game']['mage']['items'])!!}'>
    </div>
</div>

