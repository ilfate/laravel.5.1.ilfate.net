
<div class="spellBook-message-container"></div>
<div class="craft-spell-overlay-blender">
    <div class="alert alert-warning alert-dismissible helper-spell-craft-blender" role="alert">
        You can blend 3 spells of the same school with more then 5 usages.
    </div>
</div>
<div class="spellBook">

    <div class="spells-filter-panel" >
        @foreach($viewData['game']['mage']['spellSchools'] as $schoolId => $schoolConfig)
            <div class="spell-filter school-{{$schoolId}}" data-school="{{$schoolId}}">
                <div class="svg svg-replace" data-svg="{{$schoolConfig['icon']}}" data-color="{{$schoolConfig['color']}}">
                    <svg class="svg-icon " viewBox="0 0 512 512">
                    </svg>
                </div>
            </div>
        @endforeach
    </div>
    <div class="clear"></div>
    <div class="spells" data-spells='{!! json_encode($viewData['game']['mage']['spells'])!!}'>
    </div>
</div>

