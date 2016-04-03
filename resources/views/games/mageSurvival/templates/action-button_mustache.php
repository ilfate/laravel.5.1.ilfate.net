

<script id="template-action-button" type="x-tmpl-mustache">

    <div class="action method-{{method}} {{#key}}key-{{key}}{{/key}}" data-method="{{method}}">
        <a>
            <span class="text">{{name}}{{#key}} [{{key}}]{{/key}}</span>
            <div class="svg">
                <svg class="svg-icon" viewBox="0 0 550 550">
                </svg>
            </div>
        </a>
    </div>
</script>