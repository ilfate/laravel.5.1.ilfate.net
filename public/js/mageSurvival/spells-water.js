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

    this.createCastingSphere = function(x, y, icon, color)
    {
        var castSphere = this.spells.createIcon(icon).addClass('casting-sphere');

        $('.animation-field').append(castSphere);

        var path = castSphere.find('path');
        castSphere.css({
            'margin-left': x * MageS.Game.cellSize * MageS.Game.rem,
            'margin-top': y * MageS.Game.cellSize * MageS.Game.rem,
        });
        path.css({'fill': 'none', 'stroke': color, 'stroke-width': '1rem', 'stroke-opacity': 1});
        var pathEl = path[0];
        var segment = new Segment(pathEl);
        segment.draw(0, 0, 0);
        segment.draw('100%', '110%', 1.5);
        return segment;
    }

    this.startStandartWater = function() {
        var d = $('.battle-border .mage').data('d');
        var x1 = 0, y1 = 0, x2 = 0, y2 = 0;
        var distance = 0.7;
        switch(d) {
            case 1:
            case 3:
                y1 = -distance;
                y2 = distance;
                break;
            case 0:
            case 2:
                x1 = -distance;
                x2 = distance;
                break;
        }
        this.spells.savedData[0] = this.createCastingSphere(x1, y1, 'icon-bullet-sphere-cast', '#37A4F9');
        this.spells.savedData[1] = this.createCastingSphere(x1, y1, 'icon-bullet-sphere-cast-2', '#fff');
        this.spells.savedData[2] = this.createCastingSphere(x1, y1, 'icon-bullet-sphere-cast-3', '#37A4F9');
        this.spells.savedData[3] = this.createCastingSphere(x2, y2, 'icon-bullet-sphere-cast', '#37A4F9');
        this.spells.savedData[4] = this.createCastingSphere(x2, y2, 'icon-bullet-sphere-cast-2', '#fff');
        this.spells.savedData[5] = this.createCastingSphere(x2, y2, 'icon-bullet-sphere-cast-3', '#37A4F9');
        setTimeout(function(){
            MageS.Game.spells.tryToEndFirstPart();
        }, 1500);
    };
    this.iterateStandertWater = function() {
        for (var n in this.spells.savedData) {
            var segment = this.spells.savedData[n];
            segment.draw(0, 0, 0);
            segment.draw('100%', '120%', 1.5);
        }
        setTimeout(function(){
            MageS.Game.spells.tryToEndFirstPart();
        }, 1500);
    };
    this.finishStandartWater = function() {
        $('.casting-sphere').remove();
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

