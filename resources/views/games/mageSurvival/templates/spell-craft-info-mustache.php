

<script id="template-spell-craft-info" type="x-tmpl-mustache">

    <div class="alert alert-warning alert-dismissible spell-craft-info" role="alert">
        <button type="button" class="close" onclick="MageS.Game.spellbook.cancelCrafting()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="text-1">
            Ready to create spell? <br>
            Here are your chances:
        </div>
        <div class="craft-items items"></div>
        <div class="text-2">
        {{#stats}}
            <ul>
            {{#spell}}
                <li>Chane to successfully create spell: <strong>{{spell}}%</strong></li>
            {{/spell}}
            <li>Amount of usages: <strong>{{usagesMin}}-{{usagesMax}}</strong></li>
            {{#isSchool}}
                {{#school}}
                    <li>+ <strong>{{value}}</strong> additional chance to create <strong>{{name}}</strong> spell</li>
                {{/school}}
            {{/isSchool}}
            {{#isCooldown}}
                {{#cooldown}}
                    <li> {{cooldown.min}} to min cooldown and {{cooldown.max}} to max cooldown</li>
                {{/cooldown}}
            {{/isCooldown}}
            </ul>
        {{/stats}}
        </div>
        <a class="confirm-create-spell btn white">Create</a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a class="btn white" onclick="MageS.Game.spellbook.cancelCrafting()">Cancel</a>
    </div>
</script>