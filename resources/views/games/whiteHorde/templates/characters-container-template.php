
<script id="template-characters-container" type="text/x-templat">
    <div class="characters-container">
        <div class="character" v-for="character in characters" @click="character.click()">
            <div class="image"></div>
            {{character.name}}
        </div>
    </div>
</script>

