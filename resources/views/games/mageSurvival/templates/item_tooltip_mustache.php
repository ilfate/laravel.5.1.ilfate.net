

<script id="template-item-tooltip" type="x-tmpl-mustache">

    <div class="item-tooltip id-{{id}} item-{{item}}" data-id="{{id}}">
        <p>{{name}}</p>
        <p>
            {{#stats}}
              <div>
              {{#usages}}
                Spell would have <strong>{{usages}} usages</strong>.
              {{/usages}}
              {{#spell}}
                Chance to successfully create spell: <strong>{{spell}}%</strong>.
              {{/spell}}
              {{#school}}
                Additional chance to create spell of:
                {{#fire}}
                    <strong>Fire(+{{fire}})</strong>
                {{/fire}}
              {{/school}}
              {{#cooldown}}
                {{#min}}
                    Add {{min}} to minimum result spell cooldown.
                {{/min}}
                {{#max}}
                    Add +{{max}} to maximum result spell cooldown.
                {{/max}}
              {{/cooldown}}
              </div>
        {{/stats}}
        </p>
    </div>
</script>