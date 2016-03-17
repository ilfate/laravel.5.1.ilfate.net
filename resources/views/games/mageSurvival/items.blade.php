
<div class="inventory-message-container"></div>
<div class="inventory">

    <div class="craft-spell-overlay"></div>
    <div class="items-filters-panel">
        @foreach($viewData['game']['item-types'] as $id => $type)
            <span class="items-filter name-{{$type['name']}}" data-name="{{$type['name']}}">
                <svg class="svg-icon">
                    <use xlink:href="/images/game/mage/game-icons.svg#{{$type['icon']}}"></use>
                </svg>
            </span>
        @endforeach
    </div>


    <div class="items" data-items='{!! json_encode($viewData['game']['mage']['items'])!!}'>
    </div>
</div>

