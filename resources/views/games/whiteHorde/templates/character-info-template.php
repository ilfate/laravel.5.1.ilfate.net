
<script id="template-character-info" type="text/x-templat">
    <div class="character-info">
        <div class="close" @click="$emit('close')">
            <i class="fa fa-chevron-left" aria-hidden="true"></i>
        </div>
        <div class="top-part">
<!--            <img src="/images/game/WhiteHorde/man.png" />-->
<!--            <div class="items-switch">-->
<!--                <a>items</a>-->
<!--                <a>character</a>-->
<!--            </div>-->
            <div class="left-part">
                <div class="nav-container">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#character-tab" aria-controls="character-tab" role="tab" data-toggle="tab">Character</a>
                        </li>
                        <li role="presentation">
                            <a href="#items-tab" aria-controls="items-tab" role="tab" data-toggle="tab">Items</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="character-tab">
                        <img class="character-image" src="/images/game/WhiteHorde/man.png" />
                    </div>
                    <div role="tabpanel" class="tab-pane fade in" id="items-tab">
                        <item-slot
                            v-bind:character="character"
                            v-bind:type="'head'"
                        ></item-slot>
                        <item-slot
                            v-bind:character="character"
                            v-bind:type="'body'"
                        ></item-slot>
                        <item-slot
                            v-bind:character="character"
                            v-bind:type="'hand'"
                        ></item-slot>
                        <item-slot
                            v-bind:character="character"
                            v-bind:type="'offHand'"
                        ></item-slot>
                    </div>
                </div>

            </div>
            <h3>{{ character.name }}</h3>
            <div>
                <div class="attribute">Gender: {{character.gender}}</div>
                <div class="attribute">Age: {{character.age}}</div>
            </div>
        </div>
            <p>
                <div class="trait" v-for="trait in character.traits">
                    {{trait}}
                </div>
            </p>
        
    </div>
</script>

