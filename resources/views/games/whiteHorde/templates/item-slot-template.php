
<script id="template-item-slot" type="text/x-templat">
    <div 
        :class="'item-slot ' + type"
        :data-type="type"
        v-on:drop="addItem($event, character)"
        v-on:dragover="allowDrop($event)">
        <item
            :data-type="type"
            v-if="character.inventory[type]"
            :item="character.inventory[type]"
            :showQuantity="false"
            ></item>
    </div>
</script>

