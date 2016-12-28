@extends('games.whiteHorde.layout')

@section('content')

<div class="WhiteHorde-main-wrapper">
    <div class="game-screen">
        <div id="wh-menu">

        </div>
        <div id="game-app">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#settlement-tab" aria-controls="settlement-tab" role="tab" data-toggle="tab">Settlement</a>
                </li>
                <li role="presentation">
                    <a href="#raid-tab" aria-controls="raid-tab" role="tab" data-toggle="tab">Raid</a>
                </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="settlement-tab">
                    <transition name="slide-fade">
                        <inventory
                                v-if="showSettlementInventory"
                                @close="showSettlementInventory = false"
                                v-bind:items="settlementItems"
                                v-bind:resources="settlementResources"
                        ></inventory>
                    </transition>
                    <transition name="slide-left-fade">
                        <character-info
                                v-if="showCharacterInfo"
                                @close="showCharacterInfo = false"
                                v-bind:character="characterInfo"
                        ></character-info>
                    </transition>
                    <div class="buildings-container">
                        <div v-for="building in buildings" class="building" @click=buildingClick(building)>

                        </div>
                    </div>
                    <div class="characters-container"></div>
                    <characters-container
                        v-bind:characters="settlementCharacters"
                    ></characters-container>

                </div>
                <div role="tabpanel" class="tab-pane fade" id="raid-tab">
                    <div class="characters-container"></div>
                    <characters-container></characters-container>
                    <inventory></inventory>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div id="chat-app">
            <div v-for="message in messages" class="message">
                @{{message.text}}
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="userName" value="{{$userName}}" />

@stop
