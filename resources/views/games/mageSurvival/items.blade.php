
<div class="inventory-message-container"></div>
<div class="inventory">

    <div class="craft-spell-overlay"></div>
    <div class="items-filters-panel">
        @foreach($viewData['game']['item-types'] as $id => $type)
            <span class="items-filter name-{{$type['name']}}" data-name="{{$type['name']}}">
                <i class="rpg-icon-large {{$type['class']}}"></i>
            </span>
        @endforeach
    </div>


    <div class="items" data-items='{!! json_encode($viewData['game']['mage']['items'])!!}'>
    </div>
</div>

