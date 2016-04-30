/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Spells.Fire = function (game, spells) {
    this.game = game;
    this.spells = spells;

    this.startStandartFire = function()
    {
        info('Standart Fire start');
        for (var i = 0; i < 4; i++)
        {
            var svg = this.spells.createIcon('icon-flame', 'color-red');
            var radius = this.game.cellSize / 2;
            switch (i) {
                case 0: svg.css('margin-top', '-' + radius + 'rem'); break;
                case 1: svg.css('margin-left', radius + 'rem'); break;
                case 2: svg.css('margin-top', radius + 'rem'); break;
                case 3: svg.css('margin-left', '-' + radius + 'rem'); break;
            }
            $('.animation-field').append(svg);
            var path = svg.find('path')
            path.css({'fill': 'none', 'stroke': '#F07818', 'stroke-width': '1.5rem', 'stroke-opacity': 1});
            var pathEl = path[0];
            var segment = new Segment(pathEl);

            segment.draw("0", "0", 0);
            segment.draw("0", "100%", 0.5);
            this.spells.savedData[i] = svg;
        }
        setTimeout(function(){
            MageS.Game.spells.fire.iterateStandartFire();
        }, 500);


        //this.game.monimations.blastInScale(svg.find('svg.svg-icon'), 1.5, function() {
        //    MageS.Game.spells.fire.iterateStandartFire();
        //});

    };

    this.iterateStandartFire = function() {
        info('FIRE standart iterate');
        for (var i = 0; i < 4; i++) {
            var svg = this.spells.savedData[i];
            if (i == 3) {
                this.game.monimations.rotate(svg, 0, 360, 300, false, false, function() {
                    MageS.Game.spells.tryToEndFirstPart();
                })
            } else {
                this.game.monimations.rotate(svg, 0, 360, 300, false, false);
            }
        }
    };

    this.standartFireToMiddle = function() {
        for (var i = 0; i < 4; i++) {
            var svg = this.spells.savedData[i];
            this.game.monimations.rotate(svg, 0, 360, 300, false, false);
            svg.animate({'margin':0}, {duration:300, complete:function() {
                $(this).animate({opacity:0}, {duration:100, complete:function() {
                    $(this).remove();
                }});
            }
            });
        }
    };

    this.finishFireNova = function(data) {
        this.standartFireToMiddle();
        this.blastSunRing('color-red-bright');
        setTimeout(function() {
            MageS.Game.spells.fire.blastSunRing('color-yellow');
        }, 100);
        setTimeout(function() {
            MageS.Game.spells.fire.blastSunRing('color-white');
        }, 200);
        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 1400);
    };

    this.blastSunRing = function(color) {
        var svg = this.spells.createIcon('icon-sun-fire', color);
        $('.animation-field').append(svg);
        this.game.monimations.blastInScale(svg.find('svg.svg-icon'), 6, null, 1200);
        setTimeout(function() {
            svg.animate({opacity:0}, {duration:400, complete: function() {
                $(this).remove();
            }});
        }, 800);
    };

    this.beamStrike = function(length, deg, svgline, color, options) {
        if (!options) { options = {}; }
        deg -= 45;
        var beam = this.spells.createIcon(svgline).addClass('beam');
        beam[0].style.transform = ' rotate(' + deg +'deg)';
        var icon = beam.find('.svg-icon');
        var moveTop = '';
        if (options.moveTop !== undefined) {
            moveTop = options.moveTop;
        } else {
            moveTop = this.game.cellSize / 2 + 'rem';
        }
            var moveLeft = '';
        if (options.moveLeft !== undefined) {
            moveLeft = options.moveLeft;
        } else {
            moveLeft = this.game.cellSize / 2 + 'rem';
        }
        beam.css({'width':'1px','height':'1px', 'margin-left': moveLeft, 'margin-top': moveTop});
        var side = length / Math.sqrt(2);
        beam.find('svg').css({'width':side * this.game.cellSize + 'rem', 'height':side * this.game.cellSize + 'rem'});
        icon.css({'position':'absolute'});

        $('.animation-field').append(beam);

        var path = beam.find('path');
        var baseBeamWidth = 10;
        if (options.beamWidth !== undefined) { baseBeamWidth = options.beamWidth; }
        var strokeWidth = (baseBeamWidth - length) / 10;
        path.css({'fill': 'none', 'stroke': color, 'stroke-width': strokeWidth + 'rem', 'stroke-opacity': 1});
        var pathEl = path[0];
        var segment = new Segment(pathEl);
        var time = 0.8;
        if (options.time !== undefined) { time = options.time}
        segment.draw("0", "0", 0);
        var delay = 0;
        if (options.delay !== undefined) { delay = options.delay; }
        setTimeout(function() {
            segment.draw("100%", "150%", time);
        }, delay);
    };

    this.finishExplodingBees = function(data) {
        this.standartFireToMiddle();

        var rad = Math.atan2(data.targetY, data.targetX); // In radians
        //Then you can convert it to degrees as easy as:
        var deg = rad * (180 / Math.PI);
        var distance = Math.sqrt(Math.pow(data.targetX, 2) + Math.pow(data.targetY, 2));

        MageS.Game.spells.fire.beamStrike(distance, deg, 'icon-sinusoidal-line', '#F07818');
        setTimeout(function() {
            MageS.Game.spells.fire.beamStrike(distance, deg, 'icon-bullet-around-side-line', '#F07818');
        }, 50);
        setTimeout(function() {
            MageS.Game.spells.fire.beamStrike(distance, deg, 'icon-bullet-backward-line', '#F07818');
        }, 100);
        setTimeout(function() {
            MageS.Game.spells.fire.beamStrike(distance, deg, 'icon-bullet-cercle-line', '#F07818');
        }, 200);
        setTimeout(function() {
            MageS.Game.spells.fire.beamStrike(distance, deg, 'icon-bullet-around-line', '#F07818');
        }, 250);
        setTimeout(function() {
            $('.animation-field .beam').remove();
        }, 1200);
        //var beam = this.spells.createIcon('icon-sinusoidal-line', 'color-red');

        var blastIcon = this.spells.createIcon('icon-fireflake', 'color-red').addClass('blast').css({
            'margin-left': data.targetX * this.game.cellSize + 'rem',
            'margin-top': data.targetY * this.game.cellSize + 'rem'
        });
        var blastSvg = blastIcon.find('svg.svg-icon');
        blastSvg[0].style.transform = 'scale(0)';
        $('.animation-field').append(blastIcon);
        setTimeout(function() {
            MageS.Game.monimations.blastInScale(blastSvg, 1, function() {
                $('.animation-field .blast').animate({opacity:0}, {duration:300, complete:function() {
                    $(this).remove();
                    MageS.Game.spells.endSpellAnimation();
                }})
            }, 500);
        }, 500);
    };

    this.finishFaceCanon = function(data) {
        this.standartFireToMiddle();

        var shakeDirection = 'X';
        if (data.d == 0 || data.d == 2) {
            shakeDirection = 'Y';
        }
        var slash = this.spells.createIcon('icon-quick-slash');

        $('.animation-field').append(slash);
        var deg = 0;
        var mLeft = 0;
        var mTop = 0;
        var rTop = 0;
        var rLeft = 0;
        var rTopFinal = 0;
        var rLeftFinal = 0;
        switch (data.d) {
            case 0: deg = -90; mTop = -1.5; rLeft = 3; break;
            case 1: mLeft = 1.5; rTop = 3; break;
            case 2: deg = 90; mTop = 1.5; rLeft = 3; break;
            case 3: deg = 180; mLeft = -1.5; rTop = 3; break;
        }
        slash.animate({'margin-left': (this.game.cellSize * mLeft) + 'rem',
            'margin-top': (this.game.cellSize * mTop) + 'rem'}, {duration: 1200});
        slash[0].style.transform='rotate(' + deg + 'deg)';
        this.game.monimations.blastInScale(slash.find('svg.svg-icon'), 6, null, 1200);
        slash.find('path').attr('fill', '#ff3f03').animate(
            {opacity:0, 'svgFill': '#fff'},
            {duration:1050, easing: 'easeInExpo'}
        );

        var svgContEl = $('<div class="animation animation-centred-block"></div>');
        for (var i = 0; i <= 5; i++ ) {
            var svgEl = $('<div class="circle n-' + i + '" data-n="' + i + '"></div>').width('0').height('0').css({'position':'absolute'});
            svgEl.svg({
                onLoad: function (svg) {
                    svg.circle(0, 0, 0.1 * MageS.Game.rem,
                        {fill: '#ff3f03', stroke: 'none'});
                }
            });
            if (rTop) { rTopFinal = rTop - (i * rTop / 2.5); }
            if (rLeft) { rLeftFinal = rLeft - (i * rLeft / 2.5); }
            svgEl.find('svg circle').delay(550).animate({
                'svgCx':(mLeft * 5 + rLeftFinal) * MageS.Game.cellSize * MageS.Game.rem,
                'svgCy':(mTop * 5 + rTopFinal) * MageS.Game.cellSize * MageS.Game.rem,
            }, {duration:400});
            svgContEl.append(svgEl);
        }
        $('.animation-field').append(svgContEl);

        MageS.Game.monimations.camShake(shakeDirection, 500, 6, 800);

        setTimeout(function() {
                    MageS.Game.spells.endSpellAnimation();
        }, 1200);
    };
    
    this.doPhoenixStep = function(phoenix, data, step, d) {
        var currentPoint = data[step].point;
        var targets = [];
        if (data[step].targets !== undefined) {
            targets = data[step].targets;
            for( var targetNum in targets) {
                var calculations = this.spells.getDistanceBetweenTwoDots(currentPoint[0], currentPoint[1], targets[targetNum][0], targets[targetNum][1]);
                var options = {
                    'moveTop': ((currentPoint[1] + 0.5) * MageS.Game.cellSize) + 'rem',
                    'moveLeft': ((currentPoint[0] + 0.5) * MageS.Game.cellSize) + 'rem',
                    'time' : 1,
                    'delay': 500,
                    'beamWidth': 15,
                };
                var bulletType = 'icon-bullet-simple-middle-line';
                if (Math.random() > 0.5) { bulletType = 'icon-sinusoidal-line'; }
                    MageS.Game.spells.fire.beamStrike(calculations[1], calculations[0], bulletType, '#F07818', options);
            }
        }

        phoenix.animate({
            'margin-left': (currentPoint[0] * MageS.Game.cellSize) + 'rem',
            'margin-top': (currentPoint[1] * MageS.Game.cellSize) + 'rem',
        }, {
            duration: 500, easing:'linear', complete: function () {
                if (data[step + 1] !== undefined) {
                    MageS.Game.spells.fire.doPhoenixStep(phoenix, data, step + 1, d);
                } else {
                    var addX = 0;
                    var addY = 0;
                    switch (d) {
                        case 0: addY -= 5; break;
                        case 1: addX += 5; break;
                        case 2: addY += 5; break;
                        case 3: addX -= 5; break;
                    }
                    phoenix.animate({
                        'opacity':0,
                        'margin-left': ((currentPoint[0] + addX) * MageS.Game.cellSize) + 'rem',
                        'margin-top': ((currentPoint[1] + addY) * MageS.Game.cellSize) + 'rem',},
                        {duration:1000, complete: function() {
                            MageS.Game.spells.fire.clearPhoenix();
                        }});
                }
            }
        })
    };
    this.clearPhoenix = function () {
        MageS.Game.spells.endSpellAnimation();
    };
    this.finishPhoenixStrike = function(data) {
        this.standartFireToMiddle();

        var phoenix = this.spells.createIcon('icon-crow-dive', 'color-red').addClass('phoenix');
        $('.animation-field').append(phoenix);
        var angle = -45;
        switch (data.d) {
            case 0: angle -= 90; break;
            case 2: angle += 90; break;
            case 3: angle += 180; break;
        }
        phoenix[0].style.transform = 'rotate(' + angle + 'deg)';

        this.doPhoenixStep(phoenix, data.data, 0, data.d);
    };





    this.startFireball = function() {
        var svgContEl = $('<div class="animation animation-centred-block"></div>');
        for (var i = 0; i < 3; i++ ) {
            var svgEl = $('<div class="circle n-' + i + '" data-n="' + i + '"></div>').width('0').height('0').css({'position':'absolute'});
            svgEl.svg({
                onLoad: function (svg) {
                    svg.circle(0, 0, 0.25 * MageS.Game.rem,
                        {fill: 'none', stroke: '#F07818'});
                }
            });
            svgEl.find('svg circle').animate({ 'svgR':(0.75 + (i*0.25)) * this.game.rem}, {duration:250});
            svgContEl.append(svgEl);
        }
        for (var i = 0; i < 2; i++ ) {
            var svgEl = $('<div class="fire-dot n-' + i +'"></div>').width('0').height('0').css({'position':'absolute'});
            svgEl.svg({
                onLoad: function (svg) {
                    svg.circle(0, 0, 0.1 * MageS.Game.rem,
                        {fill: '#F07818', stroke: 'none'});
                }
            });
            svgEl.find('svg circle').animate({ 'svgCx':(1 + (i*0.25)) * this.game.rem},
                {duration:250});
            MageS.Game.monimations.rotate(svgEl, 0, 360, 250, i == 0, false);
            svgContEl.append(svgEl);
        }
        setTimeout(function() {
            MageS.Game.spells.fire.iterateFireball();
        }, 260);

        this.spells.savedData[0] = true;
        $('.animation-field').prepend(svgContEl);
    };

    this.iterateFireball = function() {
        var iterateTime = 400;
        var animEl = $('.animation-field .animation');

        MageS.Game.monimations.rotate(animEl.find('.fire-dot.n-0'), 0, 360, iterateTime, true, false, function() {
            MageS.Game.spells.tryToEndFirstPart();
        });
        MageS.Game.monimations.rotate(animEl.find('.fire-dot.n-1'), 0, 360, iterateTime, false, false);
        if (this.spells.savedData[0]) {var color = '#FFF';} else {var color = '#F07818';}
        this.spells.savedData[0] = ! this.spells.savedData[0];
        animEl.find('.fire-dot circle').animate({'svgStroke':color}, {duration: iterateTime});
    };

    this.finishFireball = function(data) {
        var iterateTime = 400;
        var animEl = $('.animation-field .animation');

        MageS.Game.monimations.rotate(animEl.find('.fire-dot.n-0'), 180 + Math.random() * 180, 180, 500, true, false);
        MageS.Game.monimations.rotate(animEl.find('.fire-dot.n-1'), 180 + Math.random() * 180, 180, 500, false, false);

        animEl.find('.fire-dot circle').animate({'svgCx':0}, {duration: 350, complete: function() {
            animEl.find('.fire-dot').remove();
            //}, easing: 'easeInOutBack'});
        }, easing: 'easeInBack'});

        var rad = Math.atan2(data.targetY, data.targetX); // In radians
        //Then you can convert it to degrees as easy as:
        var deg = rad * (180 / Math.PI);

        var easings = ['easeOutCubic', 'easeOutQuart','easeOutExpo'];
        var range = Math.round(Math.sqrt(Math.pow(data.targetX, 2) + Math.pow(data.targetY, 2)) * this.game.cellSize);
        for (var i = 0; i < 3; i++ ) {
            animEl.find('.circle.n-' + i + ' circle').animate({'svgR': (0.1 + i * 0.1) * this.game.rem }, {
                duration: 200, complete: function () {
                    var obj = $(this);
                    var n = obj.parents('.circle').data('n');
                    obj.css({'stroke': 'none', 'fill': '#F07818'});

                    obj.parent()[0].style.transform = 'rotate(' + deg + 'deg)';
                    obj.parent()[0].style['transform-origin'] = '0% 0%';
                    setTimeout( function() {
                        obj.animate({'svgCx': range * MageS.Game.rem}, {duration: 400, 'easing': easings[n]});
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

