
<script id="template-building-window" type="text/x-templat">
    <div class="building-window">
        <div class="close" @click="$emit('close')">
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </div>
        <inventory
            v-if="building.type=='warhouse'"
            :items="building.settlement.items"
            :resources="building.settlement.resources"
            ></inventory>
        <div class="slots" v-if="building.slots">
            <building-slot
                v-for="slot in building.slots"
                :slotVar="slot"
                :building="building"
            ></building-slot>
        </div>
    </div>
</script>

