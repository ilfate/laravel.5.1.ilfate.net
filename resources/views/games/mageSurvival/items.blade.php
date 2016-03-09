
            <div class="inventory-message-container"></div>
            <div class="inventory">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach($viewData['game']['mage']['items'] as $type => $items)
                            <li role="presentation"><a href="#items-tab-{{$type}}" aria-controls="items-tab-{{$type}}" role="tab" data-toggle="tab">{{$type}}</a></li>
                        @endforeach
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        @foreach($viewData['game']['mage']['items'] as $type => $items)
                            <div role="tabpanel" class="tab-pane items-tab {{$type}}" id="items-tab-{{$type}}">
                                @foreach($items as $itemId => $itemInfo)
                                    <div class="item id-{{$itemId}} item-{{$itemInfo['image']}} tip"
                                         data-description='{!! json_encode($itemInfo)!!}'
                                         data-id="{{$itemId}}">
                                        {{$itemInfo['quantity']}}
                                    </div>
                                @endforeach
                            </div>

                        @endforeach
                    </div>
            </div>

