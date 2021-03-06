

<script id="template-spell-tooltip" type="x-tmpl-mustache">

    <div class="spell-tooltip id-{{id}} spell-{{spell}}" data-id="{{id}}">
        <button type="button" class="close" onclick="MageS.Game.spellbook.checkForActiveSpells()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <p class="name">{{name}}</p>
        <p class="cooldown">
            Cooldown:  <span class="value">{{cooldown}}</span>
            <span class="active">(<span class="value"></span>)</span>
        </p>
        <p>
            {{description}}
        </p>
        <div class="cast-button text">
            <a class="btn white" onclick="MageS.Game.spellbook.spellCastClick()">Cast</a>
        </div>
        <div class="spell-ingredients">
            {{#ingredients}}
            <div class="svg {{icon}}">
                <svg class="svg-icon" viewBox="0 0 512 512">
                </svg>
            </div>
            {{/ingredients}}
        </div>
    </div>
</script>