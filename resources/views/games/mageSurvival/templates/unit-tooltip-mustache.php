


<script id="template-unit-tooltip" type="x-tmpl-mustache">

    <div class="unit-tooltip id-{{id}}" data-id="{{id}}">
        <button type="button" class="close" onclick="MageS.Game.units.closeUnitTooltip()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <p>{{name}} (HP: <span class="current-health">{{health}}</span>:{{maxHealth}})</p>
        <p>
            {{description}}
        </p>
    </div>
</script>