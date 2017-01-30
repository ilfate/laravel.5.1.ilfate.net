@extends('games.whiteHorde.layout')

@section('content')

<div class="WhiteHorde-main-wrapper">
    <div class="game-screen">
        <div id="wh-menu">

        </div>
        <div id="game-app">
            <md-tabs md-centered>
                <md-tab md-label="Settlement">
                    <transition name="slide-fade">
                        <building-window
                                v-if="building.show"
                                v-for="building in buildings"
                                :building="building"
                        @close="building.show=!building.show">
                        </building-window>
                    </transition>
                    <transition name="slide-left-fade">
                        <character-info
                                v-if="characterInfo"
                        @close="characterInfo = false"
                        v-bind:character="characterInfo"
                        ></character-info>
                    </transition>
                    <div class="buildings-container">
                        <building v-for="building in buildings" v-bind:building="building">
                        </building>
                    </div>
                    <div
                            class="characters-container"
                            v-on:drop="unassignCharacter($event)"
                            v-on:dragover="allowDropCharacter($event)">
                        <character
                                v-for="character in settlementCharacters"
                                :character="character"
                                :characterInfo="characterInfo"
                        ></character>
                    </div>
                </md-tab>
                <md-tab md-label="Raid">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt dolorum quas amet cum vitae, omnis! Illum quas voluptatem, expedita iste, dicta ipsum ea veniam dolore in, quod saepe reiciendis nihil.</p>
                </md-tab>
            </md-tabs>

            <md-dialog-alert
                :md-content="alert.content"
                :md-ok-text="alert.ok"
                {{--@open="onOpen"--}}
                {{--@close="onClose"--}}
                ref="alert-ref">
            </md-dialog-alert>
            {{--<ul class="nav nav-tabs" role="tablist">--}}
                {{--<li role="presentation" class="active">--}}
                    {{--<a href="#settlement-tab" aria-controls="settlement-tab" role="tab" data-toggle="tab">Settlement</a>--}}
                {{--</li>--}}
                {{--<li role="presentation">--}}
                    {{--<a href="#raid-tab" aria-controls="raid-tab" role="tab" data-toggle="tab">Raid</a>--}}
                {{--</li>--}}
            {{--</ul>--}}

            {{--<div class="tab-content">--}}
                {{--<div role="tabpanel" class="tab-pane fade in active" id="settlement-tab">--}}
                    {{--<transition name="slide-fade">--}}
                        {{--<building-window--}}
                                {{--v-if="building.show"--}}
                                {{--v-for="building in buildings"--}}
                                {{--:building="building"--}}
                                {{--@close="building.show=!building.show">--}}
                        {{--</building-window>--}}
                    {{--</transition>--}}
                    {{--<transition name="slide-left-fade">--}}
                        {{--<character-info--}}
                                {{--v-if="characterInfo"--}}
                                {{--@close="characterInfo = false"--}}
                                {{--v-bind:character="characterInfo"--}}
                        {{--></character-info>--}}
                    {{--</transition>--}}
                    {{--<div class="buildings-container">--}}
                        {{--<building v-for="building in buildings" v-bind:building="building">--}}
                        {{--</building>--}}
                    {{--</div>--}}
                    {{--<div--}}
                        {{--class="characters-container"--}}
                        {{--v-on:drop="unassignCharacter($event)"--}}
                        {{--v-on:dragover="allowDropCharacter($event)">--}}
                        {{--<character--}}
                                {{--v-for="character in settlementCharacters"--}}
                                {{--:character="character"--}}
                                {{--:characterInfo="characterInfo"--}}
                        {{--></character>--}}
                    {{--</div>--}}


                {{--</div>--}}
                {{--<div role="tabpanel" class="tab-pane fade" id="raid-tab">--}}

                {{--</div>--}}
            {{--</div>--}}
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
