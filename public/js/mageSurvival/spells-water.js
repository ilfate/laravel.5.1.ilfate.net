/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Spells.Water = function (game, spells) {
    this.game = game;
    this.spells = spells;

    this.sparklingAnimation = function(animationEl) {
        var svgContEl = $('<div class="animation-centred-block"></div>');
        for (var i = 0; i < 6; i++ ) {
            var svgEl = $('<div></div>').width('0').height('0').css({'position':'absolute'});
            svgEl.svg({
                onLoad: function (svg) {
                    svg.circle(0, 0, 0.25 * MageS.Game.rem,
                        {fill: '#fff', stroke: 'none'});
                }
            });
            svgEl.find('svg circle').animate({'svgCx': 4 * this.game.rem, 'svgR':0.05 * this.game.rem},
                {duration:500});
            svgEl[0].style.transform = 'rotate(' + parseInt(360 / 6 * i) + 'deg)';
            svgContEl.append(svgEl);
        }
        animationEl.prepend(svgContEl);
        return svgContEl;
    };

    this.startIceCrown = function() {
        var icon = this.spells.createIcon('icon-frozen-orb', 'color-white');
        $('.animation-field').append(icon);
        this.spells.savedData[0] = icon;
        this.spells.savedData[1] = this.sparklingAnimation(icon);
        this.spells.savedData[2] = false;
        icon.find('svg.svg-icon path').animate({'svgFill': '#fff'}, {duration:1500});
        this.game.monimations.blastInScale(icon.find('svg.svg-icon'), 3, function() {
            MageS.Game.spells.water.iterateIceCrown();
        });
    };

    this.iterateIceCrown = function() {
        var iterateTime = 400;
        MageS.Game.monimations.rotate(this.spells.savedData[1], parseInt(30), 90, iterateTime, true, false);
        if (this.spells.savedData[2]) {
            var radius = 0.15 * this.game.rem; var range = 2.5 * this.game.rem;
        }
        else {
            var radius = 0.05 * this.game.rem; var range = 4 * this.game.rem;
        }
        this.spells.savedData[1].find('circle').animate({'svgCx': range, 'svgR':radius}, {duration:iterateTime});
        this.spells.savedData[2] = ! this.spells.savedData[2];

        var el = this.spells.savedData[0].find('svg.svg-icon');
        var grad = 60;
        new mojs.Tween({
            repeat:   0,
            delay:    1,
            duration: iterateTime,
            onUpdate: function (progress) {
                progress = progress * grad - grad;
                el[0].style.transform = 'scale(2.25) rotate(' + (progress) + 'deg)';

            }, onComplete: function() {
                MageS.Game.spells.tryToEndFirstPart();
            }
        }).run();
    };

    this.finishIceCrown = function (data) {
        MageS.Game.monimations.rotate(this.spells.savedData[1], parseInt(30), 150, 1000, true, false);
        this.spells.savedData[1].find('circle').animate({'svgCx': 0.5 * this.game.rem, 'svgR':0.15 * this.game.rem},
            {duration:1000, easing:'easeOutBounce'});

        var icon = this.spells.savedData[0];

        MageS.Game.monimations.rotateWithScale(icon.find('svg.svg-icon'), 0, 360, 2.25, -2, 1000);

        $('.animation-field .animation').animate({
            opacity: '0'
        }, {
            duration:1000,
            easing:'easeInExpo',
            complete: function() {
                MageS.Game.spells.endSpellAnimation();
            }
        })
    };
};

