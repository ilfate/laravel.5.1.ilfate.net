/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Spells.Earth = function (game, spells) {
    this.game = game;
    this.spells = spells;

    this.startStandartEarth = function()
    {
        var startX = 0;
        var startY = 0;
        var icon = 'icon-bullet-small-spiral';
        for (var n = 0; n < 4; n++) {
            switch (n) {
                case 0: startX = 0; startY = -1; break;
                case 1: startX = 1; startY = 0; break;
                case 2: startX = 0; startY = 1; break;
                case 3: startX = -1; startY = 0; break;
            }
            var options = {
                'moveLeft': ((0.5 + startX) * MageS.Game.cellSize) + 'rem',
                'moveTop': ((0.5 + startY) * MageS.Game.cellSize) + 'rem',
                'time': 0.3,
                'beamWidth': 12,
                'segment1': ["100%", "100%"],
                'segment2': ["-20%", "0%"],
                'delete': true
            };
            for (var i = 0; i < 3; i++) {
                this.spells.beamStrike(2.5, 360 / 3 * i, icon, MageS.Game.color.brown, options);
            }
        }

        var icons = []
        for (var i = 0; i < 4; i ++) {
            var options2 = {
                scale:0.5,
                time:600,
                rotateDistance: 90,
                delay: 200,
                angleStart:(i * 90) - 7,
                preAnimationDelay: 200
            };
            var iconObject = this.spells.spinIcon('icon-stone-sphere', 'color-brown', 0.5, options2);
            icons.push(iconObject);
        }
        this.spells.savedData[0] = icons;

        var icons2 = []
        var icons3 = []
        var icon2 = 'icon-bullet-cercle-sinus-2';
        var icon3 = 'icon-bullet-cercle-sinus';
        var options3 = {
            'moveLeft': ((0.5) * MageS.Game.cellSize) + 'rem',
            'moveTop': ((0.5) * MageS.Game.cellSize) + 'rem',
            'time': 0.6,
            'beamWidth': 10,
            'segment1': ["40%", "40%"],
            'segment2': ["24%", "16%"],
            'delay':400
        };
        for(var i = 0 ; i < 4; i++) {
            icons2.push(this.spells.beamStrike(2.7, 360 / 4 * i, icon2, MageS.Game.color.orange, options3));
            icons3.push(this.spells.beamStrike(2.9, 360 / 4 * i, icon3, MageS.Game.color.orange, options3));
        }
        this.spells.savedData[1] = icons2;
        this.spells.savedData[2] = icons3;

        setTimeout(function(){
            MageS.Game.spells.earth.iterateStandartEarth();
        }, 1000);
    };

    this.iterateStandartEarth = function() {
        for (var i in this.spells.savedData[0]) {
            var iconObject = this.spells.savedData[0][i];
            var angleStart = (i * 90) - 7;
            iconObject.animateRotate(angleStart, angleStart + 90, 600, 'linear');
        }
        for (var i2 in this.spells.savedData[1]) {
            var beamObject = this.spells.savedData[1][i2];
            var pathEl = beamObject.find('path')[0];
            var segment = new Segment(pathEl);
            segment.draw("49%", "41%", 0);
            segment.draw("24%", "16%", 0.6);
        }
        for (var i3 in this.spells.savedData[2]) {
            var beamObject = this.spells.savedData[2][i3];
            var pathEl = beamObject.find('path')[0];
            var segment = new Segment(pathEl);
            segment.draw("49%", "41%", 0);
            segment.draw("24%", "16%", 0.6);
        }
        setTimeout(function() {
            $('.battle-border .mage-container .mage .svg').css({'overflow':'visible'});
            MageS.Game.spells.tryToEndFirstPart();
        }, 600);
    };

    this.finishStandartEarth = function(time) {
        for (var i in this.spells.savedData[0]) {
            var iconObject = this.spells.savedData[0][i];
            var angleStart = (i * 90) - 7;
            iconObject.animateRotate(angleStart, angleStart + 90, time, 'linear');
            var svg = iconObject.find('svg');
            svg.animate({'margin-left' : 0, opacity:0}, {duration:time, complete:function(){ $(this).remove();}})
        }
        for (var i2 in this.spells.savedData[1]) {
            var beamObject = this.spells.savedData[1][i2];
            beamObject.animate({opacity:0}, {duration:100, complete:function(){ $(this).remove();}});
        }
        for (var i3 in this.spells.savedData[2]) {
            var beamObject = this.spells.savedData[2][i3];
            beamObject.animate({opacity:0}, {duration:100, complete:function(){ $(this).remove();}});
        }
    };

    this.finishStoneFace = function(data) {
        this.finishStandartEarth(200);

        var beam = 'icon-bullet-simple-middle-line';
        for (var i = 0; i < 5; i++) {
            var options = {
                'moveLeft': ((0.5) * MageS.Game.cellSize) + 'rem',
                'moveTop': ((0.5) * MageS.Game.cellSize) + 'rem',
                'time': 0.4,
                'beamWidth': 15,
                'segment1': ["100%", "100%"],
                'segment2': ["-40%", "0%"],
                'delete': true,
                'delay': 100 + (i * 5)
            };
            this.spells.beamStrike(4, 360 / 5 * i, beam, MageS.Game.color.brown, options)
        }

        var icon = this.spells.createIcon('icon-iron-mask', 'color-brown');
        $('.animation-field').append(icon);

        MageS.Game.monimations.blastInScale(icon, 3, function(){}, 700);

        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 1000);
    };

    this.finishGroundShake = function(data) {
        this.finishStandartEarth(200);

        this.spells.cellShake(data.pattern, {duration:350, amplitude:10, delay:200});
        this.spells.cellShake(data.pattern, {duration:350, amplitude:8, delay:500});
        this.spells.cellShake(data.pattern, {duration:350, amplitude:5, delay:800});


        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 1000);
    };

    this.finishQuicksand = function(data) {
        this.finishStandartEarth(200);
        if (data.targets.length == 0) {
            MageS.Game.spells.endSpellAnimation();
            info ('no targets for quicksand');
            return;
        }
        var rightHand = this.spells.getRightHandCoordinates(0.7);
        var leftHand = this.spells.getLeftHandCoordinates(0.7);

        for (var i in data.targets) {
            var options = {
                'time':1,
                'segment1': ["0%", "0%"],
                'segment2': ["100%", "400%"],
                'delete': true,
            };
            this.spells.beam(
                rightHand[0], rightHand[1], data.targets[i][0], data.targets[i][1],
                MageS.Game.color.sand, 'icon-bullet-spiral-cast-line-2', options
            );
            var options = {
                'time':1,

                'segment1': ["0%", "0%"],
                'segment2': ["100%", "400%"],
                'delete': true,
            };
            this.spells.beam(
                leftHand[0], leftHand[1], data.targets[i][0], data.targets[i][1],
                MageS.Game.color.sand, 'icon-bullet-spiral-cast-line', options
            );
        }


        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 1000);
    };

    this.finishStoneSpear = function(data) {
        this.finishStandartEarth(200);

        var target = [data.targetX, data.targetY];

        var spear = this.spells.createIcon('icon-stone-spear', 'color-brown');

        $('.animation-field').append(spear);

        var calculations = this.spells.getDistanceBetweenTwoDots(0, 0, target[0], target[1]);
        info(calculations);
        spear.find('svg')[0].style.transform = 'rotate(' + (calculations[1] - 45) + 'deg)';

        var margins = this.spells.transformDegAndDistanceToMargin(calculations[1], calculations[0]);

        spear.animate({
            'margin-left' : margins[0] * MageS.Game.cellSize * MageS.Game.rem,
            'margin-top' : margins[1] * MageS.Game.cellSize * MageS.Game.rem,
        }, {duration: 400, easing:'easeInQuart', complete:function(){
            $(this).remove();
        }});

        var options = {time:400, randomRange:MageS.Game.cellSize * MageS.Game.rem, scale:0.25, rotate:true, delay:400};

        for (var i = 0; i < 20; i ++) {
            // cell = array_rand(possibleCells);
            var randTargetX = (Math.random() * target[0]) + (target[0] * 1.5);
            var randTargetY = (Math.random() * target[1]) + (target[1] * 1.5);
            this.spells.moveIcon('icon-stone-sphere', 'color-brown', target[0], target[1], randTargetX, randTargetY, options);
        }
        var shakeDirection = 'Y';
        if (Math.abs(target[0]) > Math.abs(target[1])) { shakeDirection = 'X'; }
        MageS.Game.monimations.camShake(shakeDirection, 100, 4, {delay:380});

        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 600);

    };

    this.finishTunnelTravel = function(data) {
        this.finishStandartEarth(200);



        for (var i = 0; i < 20; i ++) {
            var options = {
                time:400,
                randomRange:MageS.Game.cellSize * MageS.Game.rem * 2,
                scale:0.15,
                delay:(Math.random() * 400) + 200};
            this.spells.moveIcon('icon-stone-sphere', 'color-brown', 0, 0, 0, 0, options);
        }
        var shakeDirection = 'Y';
        //if (Math.abs(target[0]) > Math.abs(target[1])) { shakeDirection = 'X'; }
        MageS.Game.monimations.camShake(shakeDirection, 800, 6, {delay:200});

        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 800);
    };

    this.finishEarthProtection = function(data) {
        this.finishStandartEarth(200);

        var icon = 'icon-bullet-cercle-cast';
        var options = {
            'moveLeft': ((0.5) * MageS.Game.cellSize) + 'rem',
            'moveTop': ((0.5) * MageS.Game.cellSize) + 'rem',
            'time': 1,
            'beamWidth': 10,
            'segment1': ["100%", "100%"],
            'segment2': ["-50%", "0%"],
            'delete': true
        };
        for (var i = 0; i < 8; i++) {
            this.spells.beamStrike(2 + (Math.random() * 2), 360 / 8 * i, icon, MageS.Game.color.brown, options);
        }

        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 1000);
    };

    this.finishStalactitesFall = function(data) {
        this.finishStandartEarth(200);
        if (data.targets.length == 0) {
            MageS.Game.spells.endSpellAnimation();
            info ('no targets for StalactitesFall');
            return;
        }
        for (var key in data.targets) {
            var target = data.targets[key];
            var targetX = target[0];
            var targetY = target[1];
            var options = {delay:600 * key, time:300, scale:3};
            var startX = targetX;
            var startY = targetY - 16;
            this.spells.moveIcon('icon-stalactite', 'color-brown',
                startX, startY,
                targetX, targetY-1, options);
            MageS.Game.monimations.camShake('Y', 200, 8, {delay:300 + (600 * key)});
            var rand = [];

            for(var i = 0; i < 10; i++) {
                var beamOptions = {time:0.3, beamWidth: 17, 'delete':true, delay:600 * key}; // YES WE NEED IT HERE
                rand[0] = (Math.random() * 2) - 1;
                rand[1] = (Math.random() * 2) - 1;
                MageS.Game.spells.beam(startX + rand[0], startY + rand[1], targetX + rand[0], targetY + rand[1], MageS.Game.color.brown, 'icon-bullet-line', beamOptions);
            }
            var beamOptions2 = {
                'moveLeft': ((0.5 + targetX) * MageS.Game.cellSize) + 'rem',
                'moveTop': ((0.5 + targetY) * MageS.Game.cellSize) + 'rem',
                'time': 0.3,
                'beamWidth': 10,
                'segment1': ["0%", "0%"],
                'segment2': ["100%", "200%"],
                'delay': 300 + (600 * key),
                'delete':true
            };
            for(var i2 = 0 ; i2 < 10; i2++) {
                this.spells.beamStrike(2, 360 / 10 * i2, 'icon-bullet-start-spin', MageS.Game.color.brown, beamOptions2);
            }
        }


        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 600 * data.targets.length);
    };

    this.finishEarthquake = function(data) {
        this.finishStandartEarth(100);

        MageS.Game.monimations.camShake('Y', 300, 4, {delay:50});
        MageS.Game.monimations.camShake('X', 300, 8, {delay:400});
        MageS.Game.monimations.camShake('Y', 300, 12, {delay:750});

        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 1000);
    };

    this.finishAstonishing = function(data) {
        this.finishStandartEarth(200);

        var rightHand = this.spells.getRightHandCoordinates(0.7);
        var leftHand = this.spells.getLeftHandCoordinates(0.7);

        var colors = [
            MageS.Game.color.brown,
            MageS.Game.color.grey,
        ];
        var time = 0.4;
        for (var i = 0; i < 2; i++) {
            var options = {
                'time':time,
                'time2':time,
                'delay2':200,
                'segment1': ["0%", "0%"],
                'segment2': ["0%", "100%"],
                'segment3': ["100%", "200%"],
                'delete': true,
            };
            this.spells.beam(
                rightHand[0], rightHand[1], data.targetX, data.targetY,
                colors[i], 'icon-bullet-cast-angle-' + (2 + (i * 2)), options
            );
            var options2 = {
                'time':time,
                'time2':time,
                'delay2':200,
                'segment1': ["0%", "0%"],
                'segment2': ["0%", "100%"],
                'segment3': ["100%", "200%"],
                'delete': true,
            };
            this.spells.beam(
                leftHand[0], leftHand[1], data.targetX, data.targetY,
                colors[i], 'icon-bullet-cast-angle-' + (1 + (i * 2)), options2
            );
        }

        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 1000);
    };

    this.finishWallUp = function(data) {
        this.finishStandartEarth(200);
        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 200);
    };

    this.finishMilestoneHit = function(data) {
        this.finishStandartEarth(200);

        var x = parseInt(data.targetX);
        var y = parseInt(data.targetY);
        var tx = x;
        var ty = y - 2;

        var options = {
            'moveLeft': ((0.5 + tx) * MageS.Game.cellSize) + 'rem',
            'moveTop': ((0.5 + ty) * MageS.Game.cellSize) + 'rem',
            'time': 0.4,
            'beamWidth': 12,
            'segment1': ["100%", "100%"],
            'segment2': ["-100%", "0%"],
            'delay': 0,
            'delete':true
        };
        for(var i2 = 0 ; i2 < 10; i2++) {
            this.spells.beamStrike(3, 360 / 10 * i2, 'icon-bullet-cast-angle-1', MageS.Game.color.brown, options);
        }

        var icon = this.spells.createIcon('icon-stone-tablet', 'color-brown');
        icon.css({'margin-top': ((ty) * MageS.Game.cellSize) + 'rem', 'margin-left': ((tx) * MageS.Game.cellSize) + 'rem'});
        $('.animation-field').append(icon);
        MageS.Game.monimations.blastInScale(icon, 2, function(){}, 500);

        setTimeout(function(){
            icon.animate({
                'margin-top':((y) * MageS.Game.cellSize) + 'rem'
            }, {duration:400, easing:'easeOutBounce'});
        }, 500);

        MageS.Game.monimations.camShake('Y', 150, 6, {delay:700});

        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 1100);
    };


   

};

