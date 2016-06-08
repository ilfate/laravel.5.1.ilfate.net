

<script id="template-registration" type="x-tmpl-mustache">

    <form class="mage-registration">
        <button type="button" class="close" onclick="MageS.Game.cancelRegistration()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="well">Just create an account, so that you would be able to come back later.</div>
        <input type="text" class="form-control email" placeholder="email" name="email" />
        <input type="password" class="form-control password" placeholder="password" name="password" />
        <button type="button" onclick="MageS.Game.register()" class="submit btn btn-primary">Save</button>
    </form>
</script>