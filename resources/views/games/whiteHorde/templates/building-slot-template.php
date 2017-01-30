
<script id="template-building-slot" type="text/x-templat">
    <div 
        :class="'building-slot ' + slotVar.name"
        :data-type="slotVar.name"
        v-on:drop="addCharacter($event)"
        v-on:dragover="allowDrop($event)">
        <character
                v-if="building.characters[slotVar.name]"
                :character="building.characters[slotVar.name]"
        ></character>
    </div>
</script>

