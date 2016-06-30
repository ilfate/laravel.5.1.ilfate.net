

<script id="template-inventory-spell" type="x-tmpl-mustache">

    <div class="spell id-{{id}} spell-{{viewData.class}} school-{{school}}"
        data-cooldown-mark="{{cooldownMark}}"
        data-spell-type="{{spellType}}"
        data-target-type="{{targetType}}"
        data-spell="{{viewData.class}}"
        data-id="{{id}}"
        data-school="{{school}}"
    >
        <div class="svg">
            <svg class="svg-icon" viewBox="0 0 512 512">
            </svg>
        </div>
        <span class="value">{{quantity}}</span>
    </div>
</script>