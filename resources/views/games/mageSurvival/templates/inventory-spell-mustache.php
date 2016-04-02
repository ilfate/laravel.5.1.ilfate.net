

<script id="template-inventory-spell" type="x-tmpl-mustache">

    <div class="spell id-{{id}} spell-{{name}} school-{{school}}"
        data-toggle="tooltip"
        data-cooldown-mark="{{cooldownMark}}"
        data-spell-type="{{spellType}}"
        data-target-type="{{targetType}}"
        data-placement="left"
        title="{{name}}" data-id="{{id}}"
    >
        <div class="svg">
            <svg class="svg-icon" viewBox="0 0 500 500">
            </svg>
        </div>
        <span class="value">{{quantity}}</span>
    </div>
</script>