/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */

MageS.Spells = function (game) {
    this.game = game;
    this.currentSpellName = '';
    this.isSecondPartWaiting = false;
    this.spellAnimationRunning = false;
    this.stopAnimation = false;
    this.currentSpellData = {};
    this.savedData = [];
    this.fire = {};
    this.water = {};
    this.air = {};
    this.earth = {};
    this.init = function () {
        this.fire = new MageS.Spells.Fire(this.game, this);
        this.water = new MageS.Spells.Water(this.game, this);
        this.air = new MageS.Spells.Air(this.game, this);
        this.earth = new MageS.Spells.Earth(this.game, this);
    };

    this.cast = function(data, stage) {

        if (this.spellAnimationRunning) {
            this.currentSpellData = data;
            this.isSecondPartWaiting = stage;
        } else {
            info('there is no animation running for spell ' + data.spell);
            MageS.Game.animations.singleAnimationFinished(stage);
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
           case 'IceShield': this.water.startStandartWater() ; break;
           case 'Icelock': 
           case 'FreshWaterFountain':
           case 'WaterBody': this.water.startStandartWater() ; break; 
           case 'Push': 
           case 'Harmony': 
           case 'NoMoreAirForYou': 
           case 'HardLanding': 
           case 'QuardroLightning': 
           case 'Lightning': 
           case 'SkyFist':
           case 'LightingShield':
           case 'WindSword':
           case 'LootItAll':
           case 'Push2':
           case 'TeslaTrap':
           case 'ChainLighting':
               this.air.startStandartAir() ; break;
           case 'StoneFace':
           case 'GroundShake':
           case 'Quicksand':
           case 'StoneSpear':
           case 'TunnelTravel':
           case 'EarthProtection':
           case 'StalactitesFall':
           case 'Astonishing':
           case 'WallUp':
           case 'MilestoneHit':
               this.earth.startStandartEarth() ; break;
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
            case 'FireNova':
            case 'ExplodingBees':
            case 'ButthurtJump':
            case 'LightMyFire':
            case 'Bomb':
            case 'FireLady':
            case 'FaceCanon':
            case 'LetFireInYourEyes':
            case 'PhoenixStrike':
            case 'RainOfFire':
            case 'FireImp':
                this.fire.iterateStandartFire() ; break;
            case 'IceCrown': this.water.iterateIceCrown(); break;
            case 'Freeze':
            case 'IceWall':
            case 'IceSpear':
            case 'IceCone':
            case 'WashAndGo':
            case 'Blizzard':
            case 'IceShield':
            case 'Icelock': 
            case 'FreshWaterFountain': 
            case 'WaterBody':
                this.water.iterateStandertWater() ; break;
            case 'Push':
            case 'Harmony':  
            case 'NoMoreAirForYou':  
            case 'HardLanding':  
            case 'QuardroLightning':  
            case 'Lightning':  
            case 'SkyFist':
            case 'LightingShield':
            case 'WindSword':
            case 'LootItAll':
            case 'Push2':
            case 'TeslaTrap':
            case 'ChainLighting':
                this.air.iterateStandartAir() ; break;
            case 'StoneFace':
            case 'GroundShake':
            case 'Quicksand':
            case 'StoneSpear':
            case 'TunnelTravel':
            case 'EarthProtection':
            case 'StalactitesFall':
            case 'Astonishing':
            case 'WallUp':
            case 'MilestoneHit':
                this.earth.iterateStandartEarth() ; break;
            default:
                info('No iteration animation for "' + name + '"');
        }
    };
    this.continue = function(name, data) {
        if (!data) {
            data = this.currentSpellData;
        }
        switch (name) {
            case 'Fireball': this.fire.finishFireball(data); break;
            case 'FireNova': this.fire.finishFireNova(data); break;
            case 'ExplodingBees': this.fire.finishExplodingBees(data); break;
            case 'ButthurtJump': this.fire.finishButthurtJump(data); break;
            case 'LightMyFire': this.fire.finishLightMyFire(data); break;
            case 'Bomb': this.fire.finishBomb(data); break;
            case 'FireLady': this.fire.finishExplodingBees(data); break;
            case 'FaceCanon': this.fire.finishFaceCanon(data); break;
            case 'LetFireInYourEyes': this.fire.finishLetFireInYourEyes(data); break;
            case 'PhoenixStrike': this.fire.finishPhoenixStrike(data); break;
            case 'RainOfFire': this.fire.finishRainOfFire(data); break;
            case 'FireImp': this.fire.finishFireImp(data); break;
            case 'IceCrown': this.water.finishIceCrown(data); break;
            case 'Freeze': this.water.finishFreeze(data); break;
            case 'IceWall': this.water.finishIceWall(data); break;
            case 'IceSpear': this.water.finishIceSpear(data); break;
            case 'IceCone': this.water.finishIceCone(data); break;
            case 'WashAndGo': this.water.finishWashAndGo(data); break;
            case 'Blizzard': this.water.finishBlizzard(data); break;
            case 'IceShield': this.water.finishIceShield(data); break;
            case 'Icelock': this.water.finishIcelock(data); break;
            case 'FreshWaterFountain': this.water.finishFreshWaterFountain(data); break;
            case 'WaterBody': this.water.finishWaterBody(data); break;
            case 'Push': this.air.finishPush(data); break;
            case 'Harmony':  this.air.finishHarmony(data); break;
            case 'NoMoreAirForYou':  this.air.finishNoMoreAirForYou(data); break;
            case 'HardLanding':  this.air.finishHardLanding(data); break;
            case 'QuardroLightning':  this.air.finishQuardroLightning(data); break;
            case 'Lightning':  this.air.finishLightning(data); break;
            case 'SkyFist':  this.air.finishSkyFist(data); break;
            case 'LightingShield':  this.air.finishLightingShield(data); break;
            case 'WindSword':  this.air.finishWindSword(data); break;
            case 'LootItAll':  this.air.finishLootItAll(data); break;
            case 'Push2':  this.air.finishPush2(data); break;
            case 'TeslaTrap':  this.air.finishTeslaTrap(data); break;
            case 'ChainLighting':  this.air.finishChainLighting(data); break;
            case 'StoneFace':  this.earth.finishStoneFace(data); break;
            case 'GroundShake':  this.earth.finishGroundShake(data); break;
            case 'Quicksand':  this.earth.finishQuicksand(data); break;
            case 'StoneSpear':  this.earth.finishStoneSpear(data); break;
            case 'TunnelTravel':  this.earth.finishTunnelTravel(data); break;
            case 'EarthProtection':  this.earth.finishEarthProtection(data); break;
            case 'StalactitesFall':  this.earth.finishStalactitesFall(data); break;
            case 'Astonishing':  this.earth.finishAstonishing(data); break;
            case 'WallUp':  this.earth.finishWallUp(data); break;
            case 'MilestoneHit':  this.earth.finishMilestoneHit(data); break;
            default:
                info('No last animation for "' + name + '"');
                MageS.Game.animations.singleAnimationFinished(this.isSecondPartWaiting);
        }
    };
    this.tryToEndFirstPart = function() {
        if (this.isSecondPartWaiting)  {
            this.continue(this.currentSpellName);
        } else if (this.stopAnimation) {
            this.clearAnimationField();
        } else {
            this.iterate(this.currentSpellName);
        }
    };
    this.endSpellAnimation = function () {
        var stage = this.isSecondPartWaiting;
        this.clearAnimationField();
        MageS.Game.animations.singleAnimationFinished(stage);
    };
    this.clearAnimationField = function() {
        $('.battle-border .mage path.hand').show();
        $('.battle-border .mage path.active-hand').hide();
        $('.animation-field').html('');
        this.savedData = [];
        this.currentSpellName = '';
        this.isSecondPartWaiting = false;
        this.stopAnimation = false;
        this.currentSpellData = {};
        this.spellAnimationRunning = false;
    };

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
        var isSegment3 = false;
        var time2 = 0;
        if (options.segment1 !== undefined) { segment1Start = options.segment1[0]; segment1End = options.segment1[1]; }
        if (options.segment2 !== undefined) { segment2Start = options.segment2[0]; segment2End = options.segment2[1]; }
        if (options.segment3 !== undefined) { isSegment3 = true; var segment3Start = options.segment3[0]; var segment3End = options.segment3[1]; time2 = options.time2 }
        if (options.yesIWantToHaveBlinkBug === undefined) {
            segment.draw(segment1Start, segment1End, 0);
        }
        var delay = 0;
        var delay2 = 0;
        if (options.delay !== undefined) { delay = options.delay; }
        if (options.delay2 !== undefined) { delay2 = options.delay2; }
        setTimeout(function() {
            if (options.yesIWantToHaveBlinkBug !== undefined) {
                segment.draw(segment1Start, segment1End, 0);
            }
            segment.draw(segment2Start, segment2End, time);
        }, delay);
        if (isSegment3) {
            setTimeout(function () {
                segment.draw(segment3Start, segment3End, time2);
            }, delay + (time * 1000) + delay2);
        }
        if (options.delete !== undefined) {
            setTimeout(function() {
                beam.remove();
            }, delay + (time * 1000) + (time2 * 1000) + delay2)
        }
        return beam;
    };

    this.spinIcon = function(icon, color, range, options) {
        var delay = 0;
        if (options.delay !== undefined) { delay = options.delay; }
        var spinIcon = MageS.Game.spells.createIcon(icon, color).addClass('spinIcon');
        setTimeout(function(){
            $('.animation-field').append(spinIcon);

            var halfCell = 0.5 * MageS.Game.cellSize * MageS.Game.rem;
            spinIcon.css({width:'1px', height:'1px', opacity:0,
                'margin-left': halfCell,
                'margin-top': halfCell,
            });
            var svg = spinIcon.find('svg');
            if (options.rangeRandom !== undefined) { range += (Math.random() * options.rangeRandom) - (options.rangeRandom / 2) }
            svg.css({
                'margin-left' : range * MageS.Game.cellSize * MageS.Game.rem,
                'margin-top': -halfCell
            });
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
            var preAnimationDelay = 0;
            if (options.preAnimationDelay !== undefined) { preAnimationDelay = options.preAnimationDelay; }
            spinIcon[0].style.transform = 'rotate(' + angleStart +'deg)';
            setTimeout(function(){
                spinIcon.animateRotate(angleStart, angleEnd, time, 'linear');
            }, preAnimationDelay);
            spinIcon.animate({opacity:1}, {duration:100, queue:false});
            if (options.rangeMove !== undefined) {
                var rangeMove = (Math.random() * options.rangeMove * 2) - options.rangeMove;
                svg.animate({'margin-left' : (range + rangeMove) * MageS.Game.cellSize * MageS.Game.rem}, {duration:time})
            }
            if (options.delete !== undefined) {
                setTimeout(function () {
                    spinIcon.animate({opacity: 0}, {duration: 100, queue: false, complete: function() {
                        $(this).remove();
                    }});
                }, time - 100);
            }
        }, delay);
        return spinIcon;
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
            var startRandomRange = 0;
            if (options.startRandomRange !== undefined) {
                startRandomRange = options.startRandomRange;
            }
            fromX = (fromX * coordMultiplaer) + (Math.random() * startRandomRange) - (startRandomRange / 2);
            fromY = (fromY * coordMultiplaer) + (Math.random() * startRandomRange) - (startRandomRange / 2);
            flake.css({opacity:0,'margin-left':fromX, 'margin-top':fromY}); //'height': 0.25 * coordMultiplaer
            var transform = '';
            if (options.scale !== undefined) {
                transform += ' scale(' + options.scale + ', ' + options.scale + ')';
            }
            if (options.startRotate !== undefined) {
                transform += ' rotate(' + options.startRotate + 'deg)';
            }
            if (transform) {
                flake[0].style.transform = transform;
            }
            var svg = flake.find('svg');
            flake.animate({opacity:1},{duration:50});
            toX = (toX * coordMultiplaer) + (Math.random() * randomRange) - (randomRange / 2);
            toY = (toY * coordMultiplaer) + (Math.random() * randomRange) - (randomRange / 2);
            var easing = 'swing';
            if (options.easing !== undefined) { easing = options.easing; }
            flake.animate({'margin-left':toX, 'margin-top':toY}, {queue:false, duration:options.time, easing:easing });
            if (options.rotate !== undefined) {
                svg.animateRotate(0, 720, options.time);
            }
            setTimeout(function(){
                flake.fadeOut(50, function() {
                    $(this).remove();
                });
            }, options.time - 50);
        }, delay);
    };
    
    this.addScreen = function(options) {
        var delay = 0;
        if (options.delay !== undefined) {
            delay = options.delay;
        }
        setTimeout(function () {
            var duration = 100;
            if (options.duration !== undefined) {
                duration = options.duration;
            }
            var shadow = $('.animation-shadow');
            if (options.color !== undefined) {
                shadow.css({'background-color': options.color});
            }
            var opacity = 0.8;
            if (options.opacity !== undefined) {
                opacity = options.opacity;
            }
            shadow.show().animate({'opacity': opacity}, {'duration': duration, complete:function(){
                if (options.delete !== undefined) {
                    var deleteDelay = 0;
                    if (options.deleteDelay !== undefined) {
                        deleteDelay = options.deleteDelay;
                    }
                    var deleteDuration = 100;
                    if (options.deleteDuration !== undefined) {
                        deleteDuration = options.deleteDuration;
                    }
                    setTimeout(function () {
                    shadow.animate({opacity:0}, {duration: deleteDuration, complete: function() {
                        $(this).hide();
                    }})
                    }, deleteDelay);
                }
            }});
        }, delay);
    }
    
    this.cellShake = function(cells, options) {
        var x = 0;
        var y = 0;
        var amplitude = 5;
        if (options.amplitude !== undefined) {
            amplitude = options.amplitude; 
        }
        var duration = 200;
        if (options.duration !== undefined) {
            duration = options.duration; 
        }
        var delay = 0;
        if (options.delay !== undefined) {
            delay = options.delay; 
        }
        for (var i in cells) {
            x = cells[i][0];
            y = cells[i][1];
            var cellObj = $('.battle-border .cell.x-' + x + '.y-' + y);
            MageS.Game.monimations.camShake('Y', duration, amplitude, {el:cellObj, delay:delay});
        }
    }

    this.getRightHandCoordinates = function(distance) {
        var d = $('.battle-border .mage').data('d');
        var x = 0, y = 0;
        switch(d) {
            case 0:
                x = distance; break;
            case 1:
                y = distance; break;
            case 2:
                x = -distance; break;
            case 3:
                y = -distance; break;
        }
        return [x, y];
    }

    this.getLeftHandCoordinates = function(distance) {
        var d = $('.battle-border .mage').data('d');
        var x = 0, y = 0;
        switch(d) {
            case 0:
                x = -distance; break;
            case 1:
                y = -distance; break;
            case 2:
                x = distance; break;
            case 3:
                y = distance; break;
        }
        return [x, y];
    }
};

