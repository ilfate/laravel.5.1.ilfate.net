
            <div class="spellBook-message-container"></div>
            <div class="spellBook">

                <div class="spells-filter-panel" >
                    @foreach($viewData['game']['mage']['spellSchools'] as $schoolId => $schoolConfig)
                        <span class="spell-filter school-{{$schoolId}}" data-school="{{$schoolId}}">
                            <svg class="svg-icon">
                                <use xlink:href="/images/game/mage/game-icons.svg#{{$schoolConfig['icon']}}"></use>
                            </svg>
                        </span>
                    @endforeach
                </div>
                <div class="spells" data-spells='{!! json_encode($viewData['game']['mage']['spells'])!!}'>
                </div>
                    <!-- Nav tabs -->


                    <!-- Tab panes -->
                    {{--<div class="tab-content">--}}
                        {{--@foreach($viewData['game']['mage']['spells'] as $schoolId => $spells)--}}
                            {{--<div role="tabpanel" class="tab-pane spells-tab school-{{$schoolId}}" id="spells-tab-{{$schoolId}}">--}}
                                {{--@foreach($spells as $spellId => $spellInfo)--}}
                                    {{--<div class="spell id-{{$spellId}} spell-{{$spellInfo['name']}} tip"--}}
                                         {{--data-values='{!! json_encode($spellInfo)!!}'--}}
                                         {{--data-id="{{$spellId}}">--}}

                                        {{--{{$spellInfo['config']['usages']}}--}}
                                    {{--</div>--}}
                                {{--@endforeach--}}
                            {{--</div>--}}

                        {{--@endforeach--}}
                    {{--</div>--}}
            </div>

