
<script id="template-inventory" type="text/x-templat">
    <div class="inventory">
        <div class="close" @click="$emit('close')">
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </div>
        <div>
            <item v-for="item in items" v-bind:item="item">
            </item>
        </div>
    </div>
</script>

