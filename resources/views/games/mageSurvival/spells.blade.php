
<div class="spellBook-message-container"></div>
<div class="spellBook">

    <div class="spells-filter-panel" >
        @foreach($viewData['game']['mage']['spellSchools'] as $schoolId => $schoolConfig)
            <span class="spell-filter school-{{$schoolId}} {{isset($schoolConfig['class']) ? $schoolConfig['class'] : ''}}" data-school="{{$schoolId}}">
                <div class="svg svg-replace" data-svg="{{$schoolConfig['icon']}}">
                    <svg class="svg-icon " viewBox="0 0 512 512">
                    </svg>
                </div>
            </span>
        @endforeach
    </div>
    <div class="spells" data-spells='{!! json_encode($viewData['game']['mage']['spells'])!!}'>
    </div>
</div>

