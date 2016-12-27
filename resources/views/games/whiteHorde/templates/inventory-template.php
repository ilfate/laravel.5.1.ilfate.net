
<script id="template-inventory" type="text/x-templat">
    <div class="inventory">
        <div class="close" @click="$emit('close')">
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </div>
        <div>
            <div class="item" v-for="item in items">
                {{item}}
            </div>
        </div>
    </div>
</script>

