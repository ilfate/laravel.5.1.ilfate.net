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
    this.fire = {};
    this.water = {};
    this.init = function () {
        this.fire = new MageS.Spells.Fire(this.game, this);
        this.water = new MageS.Spells.Water(this.game, this);
    };

    this.cast = function(data) {

        if (this.spellAnimationRunning) {
            this.currentSpellData = data;
            this.isSecondPartWaiting = true;
        } else {
            info('there is no animation running for spell ' + data.spell);
            MageS.Game.animations.singleAnimationFinished();
        }
    };

    this.startCast = function(name) {
        var isSpellAnimated = true;
        switch (name) {
           case 'Fireball': this.fire.startFireball() ; break;
           case 'FireNova': this.fire.startStandartFire() ; break;
           case 'ExplodingBees': this.fire.startStandartFire() ; break;
           case 'ButthurtJump': this.fire.startStandartFire() ; break;
           case 'LightMyFire': this.fire.startStandartFire() ; break;
           case 'Bomb': this.fire.startStandartFire() ; break;
           case 'FireLady': this.fire.startStandartFire() ; break;
           case 'FaceCanon': this.fire.startStandartFire() ; break;
           case 'LetFireInYourEyes': this.fire.startStandartFire() ; break;
           case 'PhoenixStrike': this.fire.startStandartFire() ; break;
           case 'RainOfFire': this.fire.startStandartFire() ; break;
           case 'FireImp': this.fire.startStandartFire() ; break;
           case 'IceCrown': this.water.startIceCrown() ; break;
           case 'Freeze': this.water.startStandartWater() ; break;
           case 'IceWall': this.water.startStandartWater() ; break;
           case 'IceSpear': this.water.startStandartWater() ; break;
           case 'IceCone': this.water.startStandartWater() ; break;
           case 'WashAndGo': this.water.startStandartWater() ; break;
           case 'Blizzard': this.water.startStandartWater() ; break;
           default:
               isSpellAnimated = false;
               info('No start animation for "' + name + '"');
        }
        if (isSpellAnimated) {
            this.spellAnimationRunning = true;
            this.currentSpellName = name;
            $('.battle-border .mage path.hand').hide();
            $('.battle-border .mage path.active-hand').show();
        }
    };
    this.iterate = function(name) {
        switch (name) {
            case 'Fireball': this.fire.iterateFireball() ; break;
            case 'FireNova': this.fire.iterateStandartFire() ; break;
            case 'ExplodingBees': this.fire.iterateStandartFire() ; break;
            case 'ButthurtJump': this.fire.iterateStandartFire() ; break;
            case 'LightMyFire': this.fire.iterateStandartFire() ; break;
            case 'Bomb': this.fire.iterateStandartFire() ; break;
            case 'FireLady': this.fire.iterateStandartFire() ; break;
            case 'FaceCanon': this.fire.iterateStandartFire() ; break;
            case 'LetFireInYourEyes': this.fire.iterateStandartFire() ; break;
            case 'PhoenixStrike': this.fire.iterateStandartFire() ; break;
            case 'RainOfFire': this.fire.iterateStandartFire() ; break;
            case 'FireImp': this.fire.iterateStandartFire() ; break;
            case 'IceCrown': this.water.iterateIceCrown() ; break;
            case 'Freeze': this.water.iterateStandertWater() ; break;
            case 'IceWall': this.water.iterateStandertWater() ; break;
            case 'IceSpear': this.water.iterateStandertWater() ; break;
            case 'IceCone': this.water.iterateStandertWater() ; break;
            case 'WashAndGo': this.water.iterateStandertWater() ; break;
            case 'Blizzard': this.water.iterateStandertWater() ; break;
            default:
                info('No iteration animation for "' + name + '"');
        }
    };
    this.continue = function(name) {
        switch (name) {
            case 'Fireball': this.fire.finishFireball(this.currentSpellData); break;
            case 'FireNova': this.fire.finishFireNova(this.currentSpellData); break;
            case 'ExplodingBees': this.fire.finishExplodingBees(this.currentSpellData); break;
            case 'ButthurtJump': this.fire.finishButthurtJump(this.currentSpellData); break;
            case 'LightMyFire': this.fire.finishLightMyFire(this.currentSpellData); break;
            case 'Bomb': this.fire.finishBomb(this.currentSpellData); break;
            case 'FireLady': this.fire.finishExplodingBees(this.currentSpellData); break;
            case 'FaceCanon': this.fire.finishFaceCanon(this.currentSpellData); break;
            case 'LetFireInYourEyes': this.fire.finishLetFireInYourEyes(this.currentSpellData); break;
            case 'PhoenixStrike': this.fire.finishPhoenixStrike(this.currentSpellData); break;
            case 'RainOfFire': this.fire.finishRainOfFire(this.currentSpellData); break;
            case 'FireImp': this.fire.finishFireImp(this.currentSpellData); break;
            case 'IceCrown': this.water.finishIceCrown(this.currentSpellData); break;
            case 'Freeze': this.water.finishFreeze(this.currentSpellData); break;
            case 'IceWall': this.water.finishIceWall(this.currentSpellData); break;
            case 'IceSpear': this.water.finishIceSpear(this.currentSpellData); break;
            case 'IceCone': this.water.finishIceCone(this.currentSpellData); break;
            case 'WashAndGo': this.water.finishWashAndGo(this.currentSpellData); break;
            case 'Blizzard': this.water.finishBlizzard(this.currentSpellData); break;
            default:
                info('No last animation for "' + name + '"');
                MageS.Game.animations.singleAnimationFinished();
        }
    };
    this.tryToEndFirstPart = function() {
        if (this.isSecondPartWaiting)  {
            this.continue(this.currentSpellName);
        } else {
            this.iterate(this.currentSpellName);
        }
    };
    this.endSpellAnimation = function () {
        MageS.Game.animations.singleAnimationFinished();
        this.clearAnimationField();
    };
    this.clearAnimationField = function() {
        $('.battle-border .mage path.hand').show();
        $('.battle-border .mage path.active-hand').hide();
        $('.animation-field').html('');
        this.savedData = [];
        this.currentSpellName = '';
        this.isSecondPartWaiting = false;
        this.currentSpellData = {};
        this.spellAnimationRunning = false;
    }

    this.createIcon = function(icon, color, rotate) {
        var iconEl = $(this.game.svg).find('#' + icon + ' path');
        var svg = $('<div class="svg animation"><svg class="svg-icon" viewBox="0 0 512 512"></svg></div>');
        svg.find('svg').append(iconEl.clone());
        if (color) {
            svg.addClass(color);
        }
        if (rotate) {
            svg.find('svg').rotate(rotate + 'deg');
        }
        return svg;
    };

    this.angle_trunc = function(a) {
        while (a < 0.0) {
            a += Math.PI * 2
        }
        return a
    };
    this.getDistanceBetweenTwoDots = function(x1, y1, x2, y2) {
        var deltaY = y2 - y1;
        var deltaX = x2 - x1;
        var rad = this.angle_trunc(Math.atan2(deltaY, deltaX));
        //var rad = Math.atan2(data.targetY, data.targetX); // In radians
        //Then you can convert it to degrees as easy as:
        var deg = rad * (180 / Math.PI);
        var distance = Math.sqrt(Math.pow(deltaX, 2) + Math.pow(deltaY, 2));
        return [distance, deg];
    };
    this.transformDegAndDistanceToMargin = function(deg, distance) {
        var leftSign = 1;
        var topSign = 1;
        if (deg > 360) {
            deg -= 360;
            return this.transformDegAndDistanceToMargin(deg, distance);
        }
        else if (deg < 0) {
            deg += 360;
            return this.transformDegAndDistanceToMargin(deg, distance);
        }
        if (deg > 0 && deg <= 90) {
            // topSign = -1;
        }
        else if (deg > 90 && deg <= 180) {
            deg = 180 - deg;
            leftSign = -1;
        }
        else if (deg > 180 && deg <= 270) {
            deg = deg - 180;
            topSign = -1;
            leftSign = -1;
        }else  if (deg > 270 && deg <= 360) {
            deg = 360 - deg;
            topSign = -1;
        }
        var top = distance * Math.sin(Math.radians(deg)) * topSign;
        var left = distance * Math.sin(Math.radians(90 - deg)) * leftSign;
        return [left, top];
    };

    this.beam = function (x1,y1,x2,y2, color, lineType, options) {
        if (!options) {
            options = {};
        }

        var calculations = MageS.Game.spells.getDistanceBetweenTwoDots(x1, y1, x2, y2);

        if (options.moveTop === undefined) {
            options.moveTop = ((y1 + 0.5) * MageS.Game.cellSize) + 'rem';
        }
        if (options.moveLeft === undefined) {
            options.moveLeft = ((x1 + 0.5) * MageS.Game.cellSize) + 'rem';
        }
        if (!lineType) {
            lineType = 'icon-bullet-line';
        }
        return MageS.Game.spells.beamStrike(calculations[0], calculations[1], lineType, color, options);
    };

    this.beamStrike = function(length, deg, svgline, color, options) {
        if (!options) { options = {}; }
        deg -= 45;
        var beam = this.createIcon(svgline).addClass('beam');
        var transform = ' rotate(' + deg +'deg)';
        beam[0].style.transform = transform;
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
        var segment1Start = "0";
        var segment1End = "0";
        var segment2Start = "100%";
        var segment2End = "150%";
        if (options.segment1 !== undefined) { segment1Start = options.segment1[0]; segment1End = options.segment1[1]; }
        if (options.segment2 !== undefined) { segment2Start = options.segment2[0]; segment2End = options.segment2[1]; }
        segment.draw(segment1Start, segment1End, 0);
        var delay = 0;
        if (options.delay !== undefined) { delay = options.delay; }
        setTimeout(function() {
            segment.draw(segment2Start, segment2End, time);
        }, delay);
        return beam;
    };

    this.spinIcon = function(icon, color, range, options) {
        var delay = 0;
        if (options.delay !== undefined) { delay = options.delay; }
        setTimeout(function(){
            var spinIcon = MageS.Game.spells.createIcon(icon, color).addClass('spinIcon');
            $('.animation-field').append(spinIcon);

            var halfCell = 0.5 * MageS.Game.cellSize * MageS.Game.rem;
            spinIcon.css({width:'1px', height:'1px', opacity:0,
                'margin-left': halfCell,
                'margin-top': halfCell,
            });
            var svg = spinIcon.find('svg');
            if (options.rangeRandom !== undefined) { range += (Math.random() * options.rangeRandom) - (options.rangeRandom / 2) }
            svg.css({'margin-left' : range * MageS.Game.cellSize * MageS.Game.rem});
            if (options.scale !== undefined) {
                svg[0].style.transform = 'scale(' + options.scale + ')';
            }
            var time = 500;
            if (options.time !== undefined) {
                time = options.time;
            }
            var angleStart = 0;
            if (options.angleStart !== undefined) {
                angleStart = options.angleStart;
            } else {
                angleStart = Math.random() * 360;
            }
            var rotateDistance = 360;
            if (options.rotateDistance !== undefined) { rotateDistance = options.rotateDistance; }
            var angleEnd = angleStart + rotateDistance;
            spinIcon.animateRotate(angleStart, angleEnd, time, 'linear');
            spinIcon.animate({opacity:1}, {duration:100, queue:false});
            if (options.rangeMove !== undefined) {
                var rangeMove = (Math.random() * options.rangeMove * 2) - options.rangeMove;
                svg.animate({'margin-left' : (range + rangeMove) * MageS.Game.cellSize * MageS.Game.rem}, {duration:time})
            }
            setTimeout(function(){
                spinIcon.animate({opacity:0}, {duration:100, queue:false});
            }, time - 100);
        }, delay);
    };

    this.moveIcon = function(icon, color, fromX, fromY, toX, toY, options) {
        var delay = 0;
        if (options.delay === undefined) {
            var delayRange = 400;
            if (options.delayRange !== undefined) { delayRange = options.delayRange; }
            delay = Math.random() * delayRange;
        } else {
            delay = options.delay;
        }
        setTimeout(function(){
            var flake = MageS.Game.spells.createIcon(icon, color);
            $('.animation-field').append(flake);
            var coordMultiplaer = MageS.Game.cellSize * MageS.Game.rem;
            var randomRange = 0;
            if (options.randomRange !== undefined) {
                randomRange = options.randomRange;
            }
            fromX = (fromX * coordMultiplaer) + (Math.random() * randomRange) - (randomRange / 2);
            fromY = (fromY * coordMultiplaer) + (Math.random() * randomRange) - (randomRange / 2);
            flake.css({opacity:0,'margin-left':fromX, 'margin-top':fromY}); //'height': 0.25 * coordMultiplaer
            if (options.scale !== undefined) {
                flake[0].style.transform = 'scale(' + options.scale + ', ' + options.scale + ')';
            }
            var svg = flake.find('svg');
            flake.animate({opacity:1},{duration:50});
            toX = (toX * coordMultiplaer) + (Math.random() * randomRange) - (randomRange / 2);
            toY = (toY * coordMultiplaer) + (Math.random() * randomRange) - (randomRange / 2);

            flake.animate({'margin-left':toX, 'margin-top':toY}, {queue:false, duration:options.time});
            svg.animateRotate(0, 720, options.time);
            setTimeout(function(){
                flake.fadeOut(50);
            }, options.time - 50);
        }, delay);
    };
};

