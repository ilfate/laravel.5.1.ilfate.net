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

    this.finishFreeze = function(data) {
        this.finishStandartWater();

        var options = {
            time:400,
            randomRange:MageS.Game.cellSize * MageS.Game.rem,
            delayRange:300,
            scale: 0.25
        };
        var toX = data.targetX;
        var toY = data.targetY;
        var x = 0, y = 0;
        for (var i = 0; i < 20; i ++) {

            if (Math.random() > 0.5) {
                if (Math.random() > 0.5) { x = 5; } else { x = -5 }
                y = Math.round((Math.random() * 10) - 5);
            } else {
                if (Math.random() > 0.5) { y = 5; } else { y = -5 }
                x = Math.round((Math.random() * 10) - 5);
            }
            options.from = [x, y];
            this.spells.moveIcon('icon-snowflake-1', 'color-white', x, y, toX, toY, options);
        }

        setTimeout(function () {
            MageS.Game.spells.endSpellAnimation();
        }, 800)
    };

    

    this.finishIceWall = function(data) {
        this.finishStandartWater();
        var options = {time:500, randomRange:MageS.Game.cellSize * 1.5 * MageS.Game.rem, scale: 0.25};
        var range = 3;
        if (data.patternId == 14) { range = 2; }
        switch (data.d) {
            case 0: var fromX = 4;      var fromY = -range; var toX = -3;     var toY = -range; break;
            case 1: var fromX = range;  var fromY = -4;     var toX = range;  var toY = 3; break;
            case 2: var fromX = 4;      var fromY = range;  var toX = -3;     var toY = range; break;
            case 3: var fromX = -range; var fromY = -4;     var toX = -range; var toY = 3; break;
        }
        for (var i = 0; i < 20; i ++) {
            this.spells.moveIcon('icon-snowflake-1', 'color-white', fromX, fromY, toX, toY, options);
        }

        setTimeout(function () {
            MageS.Game.spells.endSpellAnimation();
        }, 900)
    };

    this.finishIceSpear = function(data) {
        this.finishStandartWater();

        var spear = this.spells.createIcon('icon-ice-spear', 'color-dark-blue');

        $('.animation-field').append(spear);

        var calculations = this.spells.getDistanceBetweenTwoDots(0, 0, data.targetX, data.targetY);
        spear.find('svg')[0].style.transform = 'rotate(' + (calculations[1] - 45) + 'deg)';

        var margins = this.spells.transformDegAndDistanceToMargin(calculations[1], calculations[0]);

        spear.animate({
            'margin-left' : margins[0] * MageS.Game.cellSize * MageS.Game.rem,
            'margin-top' : margins[1] * MageS.Game.cellSize * MageS.Game.rem,
        }, {duration: 400, easing:'easeInQuart', complete:function(){
            $(this).remove();
        }});
        var options = {
            segment2: ['100%', '105%'],
            time:0.50,
            delay:100
        };
        var line1 = 'icon-bullet-sinus-2';
        var line2 = 'icon-bullet-sinus';
        this.spells.beamStrike(calculations[0], calculations[1], line1, '#fff', options);
        this.spells.beamStrike(calculations[0], calculations[1], line2, '#fff', options);
        options.delay = 250;
        this.spells.beamStrike(calculations[0], calculations[1], line1, '#fff', options);
        this.spells.beamStrike(calculations[0], calculations[1], line2, '#fff', options);
        options.delay = 400;
        this.spells.beamStrike(calculations[0], calculations[1], line1, '#fff', options);
        this.spells.beamStrike(calculations[0], calculations[1], line2, '#fff', options);

        setTimeout(function () {
            MageS.Game.spells.endSpellAnimation();
        }, 900)
    };

    this.finishIceCone = function(data) {
        this.finishStandartWater();

        var options = {time:400, randomRange:MageS.Game.cellSize * MageS.Game.rem, scale:0.25};
        var possibleCells = [];
        for (var n in data.pattern) {
            if (Math.abs(data.pattern[n][0]) + Math.abs(data.pattern[n][1]) > 2) {
                possibleCells.push([data.pattern[n][0], data.pattern[n][1]]);
            }
        }

        var cell = [];
        for (var i = 0; i < 20; i ++) {
            cell = array_rand(possibleCells);
            this.spells.moveIcon('icon-snowflake-1', 'color-white', 0, 0, cell[0], cell[1], options);
        }

        setTimeout(function () {
            MageS.Game.spells.endSpellAnimation();
        }, 800)
    };

    this.finishWashAndGo = function(data) {
        this.finishStandartWater();

        var options = {beamWidth:20};

        var colors = [
            '#529BCA', '#37A4F9', '#ffffff'
        ];
        var color = '';
        var lines = [
            'icon-bullet-line-small-curve-right', 'icon-bullet-line-small-curve-left', 'icon-bullet-line', 'icon-bullet-sinus'
        ];
        var line = '';
        for (var i = 0; i < 10; i ++) {
            color = array_rand(colors);
            line = array_rand(lines);
            this.spells.beam(
                data.targetX + (Math.random() * 0.5) - 0.25,
                data.targetY + (Math.random() * 0.5) - 0.25,
                0,
                0,
                color,
                line,
                options);
        }

        setTimeout(function () {
            MageS.Game.spells.endSpellAnimation();
        }, 800)
    };

    this.finishBlizzard = function(data) {
        this.finishStandartWater();

        var options = {
            scale:0.25,
            time:300,
            rotateDistance: 180,
            rangeRandom:2,
            rangeMove: 1
        };
        for (var i = 0; i < 70; i ++) {
            options.delay = Math.random() * 1200;
            this.spells.spinIcon('icon-snowflake-1', 'color-white', 2, options);
        }

        setTimeout(function () {
            MageS.Game.spells.endSpellAnimation();
        }, 1500)
    };

    this.finishIceShield = function(data) {
        this.finishStandartWater();

        var options = {
            time:400,
            randomRange:MageS.Game.cellSize * MageS.Game.rem,
            delayRange:300,
            scale: 0.25
        };
        var x = 0, y = 0, toY = 0, toX = 0;
        for (var i = 0; i < 20; i ++) {

            if (Math.random() > 0.5) {
                if (Math.random() > 0.5) { x = 5; } else { x = -5 }
                y = Math.round((Math.random() * 10) - 5);
            } else {
                if (Math.random() > 0.5) { y = 5; } else { y = -5 }
                x = Math.round((Math.random() * 10) - 5);
            }
            options.from = [x, y];
            this.spells.moveIcon('icon-snowflake-1', 'color-white', x, y, toX, toY, options);
        }

        var icon = this.spells.createIcon('icon-ice-shield', 'color-white');
        $('.animation-field').append(icon);

        MageS.Game.monimations.blastInScale(icon, 3, function(){}, 700);

        setTimeout(function () {
            MageS.Game.spells.endSpellAnimation();
        }, 1000)
    };

    this.finishIcelock = function(data) {
        this.finishStandartWater();

        if (data.data.length == 0) {
            MageS.Game.spells.endSpellAnimation();
            info('there is no targets for Icelock');
            return;
        }

        var options = {
            segment2: ['100%', '110%'],
            time:0.80,
            delay:100
        };
        var possibleLines = [
            'icon-bullet-line',
            'icon-bullet-sinus',
            'icon-bullet-sinus-2',
            'icon-bullet-around-side-line',
            'icon-bullet-simple-right-line',
            'icon-bullet-simple-middle-line',
            'icon-bullet-simple-middle-line-2',
            'icon-bullet-line-small-curve-left',
            'icon-bullet-line-small-curve-right'
        ];

        for (var n in data.data) {
            //options.delay = Math.random() * 1200;
            this.spells.beam(0,0, data.data[n][0], data.data[n][1], '#fff', array_rand(possibleLines), options);
            this.spells.beam(0,0, data.data[n][0], data.data[n][1], '#fff', array_rand(possibleLines), options);
            this.spells.beam(0,0, data.data[n][0], data.data[n][1], '#fff', array_rand(possibleLines), options);

        }

        setTimeout(function () {
            MageS.Game.spells.endSpellAnimation();
        }, 1000)
    };

    this.finishFreshWaterFountain = function(data) {
        this.finishStandartWater();

        MageS.Game.animations.singleAnimationFinished(this.spells.isSecondPartWaiting);
        setTimeout(function(){
            MageS.Game.spells.clearAnimationField();
        }, 800);
    };

    this.finishWaterBody = function(data) {
        this.finishStandartWater();

        MageS.Game.monimations.skweeze($('.battle-border .mage'));
        setTimeout(function(){
            MageS.Game.spells.endSpellAnimation();
        }, 800);
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

