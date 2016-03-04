
            <div class="spellBook-message-container"></div>
            <div class="spellBook">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach($viewData['game']['mage']['spells'] as $schoolId => $spells)
                            <li role="presentation"><a href="#spells-tab-{{$schoolId}}" aria-controls="spells-tab-{{$schoolId}}" role="tab" data-toggle="tab">{{$schoolId}}</a></li>
                        @endforeach
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        @foreach($viewData['game']['mage']['spells'] as $schoolId => $spells)
                            <div role="tabpanel" class="tab-pane spells-tab school-{{$schoolId}}" id="spells-tab-{{$schoolId}}">
                                @foreach($spells as $spellId => $spellInfo)
                                    <div class="spell id-{{$spellId}} spell-{{$spellInfo['name']}} tip"
                                         data-toggle="tooltip"
                                         data-placement="left"
                                         title="{{$spellInfo['name']}}"
                                         data-values='{!! json_encode($spellInfo)!!}'
                                         data-id="{{$spellId}}">
                                        {{$spellInfo['config']['usages']}}
                                    </div>
                                @endforeach
                            </div>

                        @endforeach
                    </div>
            </div>

