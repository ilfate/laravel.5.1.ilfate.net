

<script id="template-spell-tooltip" type="x-tmpl-mustache">

    <div class="spell-tooltip id-{{id}} spell-{{spell}}" data-id="{{id}}">
        <button type="button" class="close" onclick="MageS.Game.spellbook.checkForActiveSpells()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <p>{{name}}</p>
        <p class="cooldown">Cooldown: <span class="value"></span></p>
        <p>
            {{description}}
        </p>
        <div class="cast-button text">
            <a class="btn white" onclick="MageS.Game.spellbook.spellCastClick()">Cast</a>
        </div>
    </div>
</script>