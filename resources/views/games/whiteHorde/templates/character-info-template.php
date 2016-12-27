
<script id="template-character-info" type="text/x-templat">
    <div class="character-info">
        <div class="close" @click="$emit('close')">
            <i class="fa fa-chevron-left" aria-hidden="true"></i>
        </div>
        <div>
            character-info<br>
            {{ character.name }}
        </div>
    </div>
</script>

