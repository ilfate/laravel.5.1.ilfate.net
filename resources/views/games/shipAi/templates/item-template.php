
<script id="template-item" type="text/x-templat">
    <div class="item"
        :title="item.name"
        :type="item.name"
        draggable="true"
        v-on:dragstart="drag($event, item)"
        :data-character="item.character ? item.character.id : ''"
        >
        <span v-if="showQuantity" class="quantity">{{ item.q }}</span>
        <img :src="item.image" draggable="false" />
    </div>
</script>

