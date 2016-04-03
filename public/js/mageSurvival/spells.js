/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Spells = function (game) {
    this.game = game;

    this.cast = function(data) {
        switch (data.spell) {
            case 'IceCrown': this.IceCrown(data); break;
            default:
                info('No animation for "' + data.spell + '"');
                MageS.Game.animations.singleAnimationFinished();
        }
    };
    this.endSpellAnimation = function () {
        MageS.Game.animations.singleAnimationFinished();
        $('.animation-field').html('');
    };

    this.addIcon = function(icon, color, rotate) {
        var iconEl = $(this.game.svg).find('#' + icon + ' path');
        var svg = $('<div class="svg animation"><svg class="svg-icon" viewBox="0 0 512 512"></svg></div>');
        svg.find('svg').append(iconEl.clone());
        if (color) {
            svg.find('path').css('fill', color);
        }
        if (rotate) {
            svg.find('svg').rotate(rotate + 'deg');
        }
        $('.animation-field').append(svg);
        return svg;
    };

    this.sparklingAnimation = function(animationEl) {
        var svgContEl = $('<div style="margin: 16px 0 0 16px;position:absolute;"></div>');
        for (var i = 0; i < 6; i++ ) {
        var svgEl = $('<div></div>').width('1px').height('1px').css({'position':'absolute'});
            svgEl.svg({
                onLoad: function (svg) {
                    svg.circle(0, 0, 5,
                        {fill: '#fff', stroke: 'none'});

                }
            });
            svgEl.find('svg circle').animate({'svgCx': 100 + 'px', 'svgR':1},
                {duration:1000});
            svgEl.find('svg circle').animate({'svgCx': 10 + 'px', 'svgR':3},
                {duration:1000, easing:'easeOutElastic'});
            svgEl[0].style.transform = 'rotate(' + parseInt(360 / 6 * i) + 'deg)';


            svgContEl.append(svgEl);
        }
        setTimeout(function() {
            MageS.Game.monimations.rotate(svgContEl, parseInt(30 + (360 / 6 * i)), 360, 1000, true);
        }, 500);
        animationEl.prepend(svgContEl);
    };

    this.IceCrown = function (data) {
        var icon = this.addIcon('icon-frozen-orb', '#529BCA');

        this.sparklingAnimation(icon);

        icon.find('svg.svg-icon path').animate({'svgFill': '#fff'}, {duration:1500});
        this.game.monimations.blastInScale(icon.find('svg.svg-icon'), 3, function() {
            MageS.Game.monimations.rotateWithScale(icon.find('svg.svg-icon'), 0, 360, 3, -2, 1000);
        });
        $('.animation-field .animation').animate({
            opacity: '0.1'
        }, {
            duration:2000,
            easing:'easeInExpo',
            complete: function() {
                MageS.Game.spells.endSpellAnimation();
            }
        })
    };


};

