
<script id="template-inventory" type="text/x-templat">
    <div>
        <div class="inventory"
            v-on:drop="storeItem($event)"
            v-on:dragover="allowDrop($event)"
        >
            <item v-for="item in items" v-bind:item="item">
            </item>
        </div>
        <div class="resources">
            <div class="resource" v-for="resource in resources">
                <span class="name">{{resource.name}}</span>
                <span class="value">{{resource.value}}</span>
                <span :class="'income ' + (resource.income < 0 ? 'red' : 'green')">{{resource.income}}</span>
            </div>
        </div>
    </div>
</script>

