/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Spells = function (game) {
    this.game = game;
    this.currentSpellName = '';
    this.isSecondPartWaiting = false;
    this.spellAnimationRunning = false;
    this.currentSpellData = {};
    this.savedData = [];

    this.cast = function(data) {
        info('Cast');

        if (this.spellAnimationRunning) {
            this.currentSpellData = data;
            this.isSecondPartWaiting = true;
        } else {
            info('there is no animation running for spell ' + data.spell);
            MageS.Game.animations.singleAnimationFinished();
        }
    };

    this.startCast = function(name) {
        info('startCast');
        var isSpellAnimated = true;
        switch (name) {
           case 'IceCrown': this.startIceCrown() ; break;
           case 'Fireball': this.startFireball() ; break;
           default:
               isSpellAnimated = false;
               info('No start animation for "' + name + '"');
        }
        if (isSpellAnimated) {
            this.spellAnimationRunning = true;
            this.currentSpellName = name;
        }
    };
    this.iterate = function(name) {
        info('iterate');
        switch (name) {
            case 'IceCrown': this.iterateIceCrown() ; break;
            case 'Fireball': this.iterateFireball() ; break;
            default:
                info('No iteration animation for "' + name + '"');
        }
    };
    this.continue = function(name) {
        info('continue');
        switch (name) {
            case 'IceCrown': this.finishIceCrown(this.currentSpellData); break;
            case 'Fireball': this.finishFireball(this.currentSpellData); break;
            default:
                info('No last animation for "' + name + '"');
                MageS.Game.animations.singleAnimationFinished();
        }
    };
    this.tryToEndFirstPart = function() {
        info('tryToEndFirstPart');
        if (this.isSecondPartWaiting)  {
            this.continue(this.currentSpellName);
        } else {
            this.iterate(this.currentSpellName);
        }
    };
    this.endSpellAnimation = function () {
        MageS.Game.animations.singleAnimationFinished();
        $('.animation-field').html('');
        this.savedData = [];
        this.currentSpellName = '';
        this.isSecondPartWaiting = false;
        this.currentSpellData = {};
        this.spellAnimationRunning = false;
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
            svgEl.find('svg circle').animate({'svgCx': 80 + 'px', 'svgR':1},
                {duration:500});
            svgEl[0].style.transform = 'rotate(' + parseInt(360 / 6 * i) + 'deg)';
            svgContEl.append(svgEl);
        }
        animationEl.prepend(svgContEl);
        return svgContEl;
    };

    this.startIceCrown = function() {
        var icon = this.addIcon('icon-frozen-orb', '#529BCA');
        this.savedData[0] = icon;
        this.savedData[1] = this.sparklingAnimation(icon);
        this.savedData[2] = false;
        icon.find('svg.svg-icon path').animate({'svgFill': '#fff'}, {duration:1500});
        this.game.monimations.blastInScale(icon.find('svg.svg-icon'), 3, function() {
            MageS.Game.spells.iterateIceCrown();
        });
    };

    this.iterateIceCrown = function() {
        var iterateTime = 400;
        MageS.Game.monimations.rotate(this.savedData[1], parseInt(30), 90, iterateTime, true, false);
        if (this.savedData[2]) {var radius = 3; var range = 50;} else {var radius = 1; var range = 80;}
        this.savedData[1].find('circle').animate({'svgCx': range, 'svgR':radius}, {duration:iterateTime});
        this.savedData[2] = ! this.savedData[2];

        var el = this.savedData[0].find('svg.svg-icon');
        var grad = 60;
        new mojs.Tween({
            repeat:   0,
            delay:    1,
            duration: iterateTime,
            onUpdate: function (progress) {
                progress = progress * grad - grad;
                el[0].style.transform = 'scale(3) rotate(' + (progress) + 'deg)';

            }, onComplete: function() {
                MageS.Game.spells.tryToEndFirstPart();
            }
        }).run();
    };

    this.finishIceCrown = function (data) {
        MageS.Game.monimations.rotate(this.savedData[1], parseInt(30), 150, 1000, true, false);
        this.savedData[1].find('circle').animate({'svgCx': 10 + 'px', 'svgR':3},
         {duration:1000, easing:'easeOutBounce'});

        var icon = this.savedData[0];

        MageS.Game.monimations.rotateWithScale(icon.find('svg.svg-icon'), 0, 360, 3, -2, 1000);

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

    this.startFireball = function() {
        var svgContEl = $('<div class="animation" style="margin: 16px 0 0 16px;position:absolute;"></div>');
        for (var i = 0; i < 3; i++ ) {
            var svgEl = $('<div class="circle n-' + i + '" data-n="' + i + '"></div>').width('1px').height('1px').css({'position':'absolute'});
            svgEl.svg({
                onLoad: function (svg) {
                    svg.circle(0, 0, 5,
                        {fill: 'none', stroke: '#F07818'});
                }
            });
            svgEl.find('svg circle').animate({ 'svgR':15 + (i*5)}, {duration:250});
            svgContEl.append(svgEl);
        }
        for (var i = 0; i < 2; i++ ) {
            var svgEl = $('<div class="fire-dot n-' + i +'"></div>').width('1px').height('1px').css({'position':'absolute'});
            svgEl.svg({
                onLoad: function (svg) {
                    svg.circle(0, 0, 2,
                        {fill: '#F07818', stroke: 'none'});
                }
            });
            svgEl.find('svg circle').animate({ 'svgCx':18 + (i*5)},
                {duration:250});
            MageS.Game.monimations.rotate(svgEl, 0, 360, 250, i == 0, false);
            svgContEl.append(svgEl);
        }
        setTimeout(function() {
            MageS.Game.spells.iterateFireball();
        }, 260);

        this.savedData[0] = true;
        $('.animation-field').prepend(svgContEl);
    };

    this.iterateFireball = function() {
        info('IterateFireball')
        var iterateTime = 400;
        var animEl = $('.animation-field .animation');

        MageS.Game.monimations.rotate(animEl.find('.fire-dot.n-0'), 0, 360, iterateTime, true, false, function() {
            MageS.Game.spells.tryToEndFirstPart();
        });
        MageS.Game.monimations.rotate(animEl.find('.fire-dot.n-1'), 0, 360, iterateTime, false, false);
        if (this.savedData[0]) {var color = '#FFF';} else {var color = '#F07818';}
        this.savedData[0] = ! this.savedData[0];
        animEl.find('.fire-dot circle').animate({'svgStroke':color}, {duration: iterateTime});
    };

    this.finishFireball = function(data) {
        var iterateTime = 400;
        var animEl = $('.animation-field .animation');

        MageS.Game.monimations.rotate(animEl.find('.fire-dot.n-0'), 180 + Math.random() * 180, 180, 500, true, false);
        MageS.Game.monimations.rotate(animEl.find('.fire-dot.n-1'), 180 + Math.random() * 180, 180, 500, false, false);

        animEl.find('.fire-dot circle').animate({'svgCx':0}, {duration: 500, complete: function() {
            animEl.find('.fire-dot').remove();
        }});


        var rad = Math.atan2(data.targetY, data.targetX); // In radians
        //Then you can convert it to degrees as easy as:
        var deg = rad * (180 / Math.PI);

        var easings = ['easeOutCubic', 'easeOutQuart','easeOutExpo'];
        var range = Math.round(Math.sqrt(Math.pow(data.targetX, 2) + Math.pow(data.targetY, 2)) * this.game.cellSize);
        for (var i = 0; i < 3; i++ ) {
            animEl.find('.circle.n-' + i + ' circle').animate({'svgR': 2 + i * 2}, {
                duration: 200, complete: function () {
                    var n = $(this).parents('.circle').data('n');
                    var obj = $(this);
                    obj.css({'stroke': 'none', 'fill': '#F07818'});

                    obj.parent()[0].style.transform = 'rotate(' + deg + 'deg)';
                    obj.parent()[0].style['transform-origin'] = '0% 0%';
                    setTimeout( function() {
                        obj.animate({'svgCx': range + 'px'}, {duration: 400, 'easing': easings[n]});
                    }, 100);
                    setTimeout( function() {
                        obj.animate({'svgTransform': 'skewX(' + 40 * n + ')', 'svgFill': '#fff'},
                            {duration:300, 'easing': easings[n], queue:false});
                    }, 420);

                }
            });
        }

        $('.animation-field .animation').animate({
            opacity: '0.9'
        }, {
            duration:1300,
            easing:'easeInExpo',
            complete: function() {
                MageS.Game.spells.endSpellAnimation();
            }
        })
    };

};

