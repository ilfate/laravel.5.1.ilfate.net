/**
 * Created by Ilya Rubinchik (ilfate) on 08/04/16.
 */




MageS.Mage = function (game) {
    this.game = game;

    this.drawMage = function(mageConf) {
        var temaplate = $('#template-mage').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'d': mageConf.d});
        var obj = $(rendered);
        var icons = this.game.getIcon('icon-mage-1');
        obj.find('svg').append(icons.clone());
        // $(this.game.svg).find('#icon-mage-1 path').each(function() {
        //     obj.find('svg').append($(this).clone());
        // });
        obj.animateRotate(0, mageConf.d * 90, 10);

        $('.mage-container').prepend(obj);
        if (mageConf.flags) {
            this.addMageStatus(mageConf.flags);
        }
        if (mageConf.firstTutorial !== undefined) {
            this.tutorialFirstMessage()
        }
    };

    this.addMageStatus = function(flags) {
        this.game.units.addFlag($('.mage-container .mage'), flags);
    };

    this.tutorialFirstMessage = function() {

        var options = {
            time: 2000,
            direction: 2,
            message: 'Move me with W A S D ',
            targetX: 0,
            targetY: 0,
            noPost: true,
            delay: 1500
        };
        MageS.Game.chat.dialogMessage(options, false);
    }

};

