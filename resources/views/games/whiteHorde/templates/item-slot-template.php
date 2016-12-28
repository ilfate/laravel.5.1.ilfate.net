
<script id="template-item-slot" type="text/x-templat">
    <div class="item-slot slot-head" :data-type="type"
        v-on:drop="addItem($event, character)"
        v-on:dragover="allowDrop($event)">
        <item
            v-if="character.inventory[type]"
            v-bind:item="character.inventory[type]"
            :showQuantity="false"
            ></item>
    </div>
</script>

