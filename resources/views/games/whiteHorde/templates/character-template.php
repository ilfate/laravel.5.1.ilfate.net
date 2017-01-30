
<script id="template-character" type="text/x-templat">
        <div 
            class="character" 
            @click="showCharacter(character)"
            draggable="true"
            v-on:dragstart="drag($event, character)"
            >
            <div class="image"></div>
            {{character.name}}
        </div>
</script>

