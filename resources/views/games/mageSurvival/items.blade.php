
            <div class="inventory-message-container"></div>
            <div class="inventory">

                    <!-- Nav tabs -->
                    <div class="items-filters-panel">
                        @foreach($viewData['game']['item-types'] as $id => $type)
                            <a class="items-filter" data-name="{{$type['name']}}"><i class="rpg-icon-large {{$type['class']}}"></i></a>
                        @endforeach
                    </div>

                    <!-- Tab panes -->
                    <div class="items" data-items='{!! json_encode($viewData['game']['mage']['items'])!!}'>
                    </div>
            </div>

