
<div class="tooltip-spell-area"></div>
<div class="tooltip-unit-area"></div>
<div class="inventory-message-container"></div>


<div class="craft-demo-zone">
    <div class="blend-spells-show-button">
        <a>Or try to blend your spells</a>
    </div>
    <a class="confirm-create-spell btn white">Create</a>
    <div class="chemical-animation"></div>
    <div class="rotating-table">
        <div class="zone-1 item-drop-zone">
            <div class="svg svg-replace color-white" data-svg="icon-select">
                <svg class="svg-icon" viewBox="0 0 512 512">
                </svg>
            </div>
        </div>
        <div class="zone-2 item-drop-zone">
            <div class="svg svg-replace color-white" data-svg="icon-select">
                <svg class="svg-icon" viewBox="0 0 512 512">
                </svg>
            </div>
        </div>
        <div class="zone-3 item-drop-zone">
            <div class="svg svg-replace color-white" data-svg="icon-select">
                <svg class="svg-icon" viewBox="0 0 512 512">
                </svg>
            </div>
        </div>
    </div>
</div>
<div class="craft-spell-overlay"></div>

<div class="inventory">

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

    <div class="clear"></div>

    <div class="items" data-items='{!! json_encode($viewData['game']['mage']['items'])!!}'>
    </div>
</div>

