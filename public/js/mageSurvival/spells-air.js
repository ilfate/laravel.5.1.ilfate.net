/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Spells.Air = function (game, spells) {
    this.game = game;
    this.spells = spells;

    this.startStandartAir = function()
    {
        var options = {
            'moveLeft': ((0.5) * MageS.Game.cellSize) + 'rem',
            'moveTop': ((0.5) * MageS.Game.cellSize) + 'rem',
            'time': 1,
            'beamWidth': 7,
            'segment1': ["100%", "100%"],
            'segment2': ["6%", "16%"],
            'delete':true
        };
        for(var i = 0 ; i < 7; i++) {
            //'icon-bullet-simple-middle-line'
            this.spells.beamStrike(5, 360 / 7 * i, 'icon-bullet-start-spin', MageS.Game.color.lightBlue, options);
        }
        setTimeout(function(){
            MageS.Game.spells.air.iterateStandartAir();
        }, 1000);
    };

    this.iterateStandartAir = function() {
        var icon = 'icon-bullet-cercle';
        var options = {
            'moveLeft': ((0.5) * MageS.Game.cellSize) + 'rem',
            'moveTop': ((0.5) * MageS.Game.cellSize) + 'rem',
            'time': 0.5,
            'beamWidth': 10,
            'segment1': ["100%", "100%"],
            'segment2': ["0%", "5%"],
            'delete': true
        };
        for(var i = 0 ; i < 7; i++) {
            //'icon-bullet-simple-middle-line'
            this.spells.beamStrike(2.2, 360 / 7 * i, icon, MageS.Game.color.lightBlue, options)
        }
        setTimeout(function() {
            MageS.Game.spells.tryToEndFirstPart();
        }, 500);
    };

    this.finishPush = function(data) {
        var icon = this.spells.createIcon('icon-cloud-ring', 'color-light-blue');
        $('.animation-field').append(icon);
        icon[0].style.transform = 'rotate(' + (data.d * 90) + 'deg)';
        this.game.monimations.blastInScale(icon.find('svg.svg-icon'), 4, null, 900);
        var left = 0, top = 0;
        switch (data.d) {
            case 0: top = -2 * MageS.Game.cellSize + 'rem'; break;
            case 1: left = 2 * MageS.Game.cellSize + 'rem'; break;
            case 2: top = 2 * MageS.Game.cellSize + 'rem'; break;
            case 3: left = -2 * MageS.Game.cellSize + 'rem'; break;
        }
        icon.animate({'margin-left': left, 'margin-top':top}, {duration:600, easing:'easeInSine'});
        MageS.Game.monimations.camShake(data.d, 200, 4, {'delay':100});
        setTimeout(function() {
            icon.animate({opacity: 0.3}, {duration:200, queue:false});
        }, 500);
        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 600);
    };

    this.finishHarmony = function(data) {
        var icon = this.spells.createIcon('icon-triorb', 'color-light-blue');
        $('.animation-field').append(icon);
        icon.css({opacity:0});
        var options = {
            'delay': 0,
            'scale': 0.4,
            time:700,
            'easing': 'easeOutCubic'
        };
        //easeOutBounce
        this.spells.moveIcon('icon-cercle', 'color-light-blue', 5, -3, 0.5, -0.1, options)
        this.spells.moveIcon('icon-cercle', 'color-light-blue', -5, -3, 0, -0.1, options)
        this.spells.moveIcon('icon-cercle', 'color-light-blue', 0, 5, 0.25, 0.25, options)


        setTimeout(function() {
            icon.animate({opacity: 1}, {duration:200, queue:false});
        }, 650);
        setTimeout(function() {
            icon.animate({opacity:0}, {duration:200});
        }, 1000);
        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 1200);
    };

    this.finishNoMoreAirForYou = function(data) {
        
        var options = {
            'moveLeft': ((0.5 + parseInt(data.targetX)) * MageS.Game.cellSize) + 'rem',
            'moveTop': ((0.5 + parseInt(data.targetY)) * MageS.Game.cellSize) + 'rem',
            'time': 1,
            'beamWidth': 12,
            'segment1': ["-10%", "0%"],
            'segment2': ["100%", "116%"],
            'delete':true
        };
        for(var i = 0 ; i < 12; i++) {
            this.spells.beamStrike(2, 360 / 12 * i, 'icon-bullet-around-side-line', MageS.Game.color.lightBlue, options);
        }
        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 1000);
    };

    this.finishHardLanding = function(data) {
        MageS.Game.animations.singleAnimationFinished(MageS.Game.spells.isSecondPartWaiting);
        var icon = 'icon-bullet-cercle';
        var options = {
            'moveLeft': ((0.5) * MageS.Game.cellSize) + 'rem',
            'moveTop': ((0.5) * MageS.Game.cellSize) + 'rem',
            'time': 0.7,
            'beamWidth': 10,
            'segment1': ["100%", "100%"],
            'segment2': ["0%", "3%"],
            'delete': true
        };
        for(var i = 0 ; i < 12; i++) {
            //'icon-bullet-simple-middle-line'
            this.spells.beamStrike(2 + (Math.random() * 0.4), 360 / 12 * i, icon, MageS.Game.color.lightBlue, options)
        }
        MageS.Game.monimations.camShake('Y', 200, 5, {'delay':700});
        var options2 = {
            'moveLeft': ((0.5) * MageS.Game.cellSize) + 'rem',
            'moveTop': ((0.5) * MageS.Game.cellSize) + 'rem',
            'time': 0.3,
            'beamWidth': 12,
            'segment1': ["0%", "0%"],
            'segment2': ["100%", "200%"],
            'delete':true,
            delay:700
        };
        for(var i2 = 0 ; i2 < 12; i2++) {
            this.spells.beamStrike(2, 360 / 12 * i2, 'icon-bullet-around-side-line', MageS.Game.color.lightBlue, options2);
        }
        setTimeout(function(){
            MageS.Game.spells.clearAnimationField();
        }, 900);
    };

    this.finishQuardroLightning = function(data) {
        if (data.targets.length == 0 ) {
            MageS.Game.spells.endSpellAnimation(); return;
        }
        var screenOptions = {color:'#000', 'delete':true, 'duration': 200, deleteDelay:300, deleteDuration:200};
        this.spells.addScreen(screenOptions);
        var icons = ['icon-bullet-lightning', 'icon-bullet-lightning-2'];
        var options = {
            // 'moveLeft': ((0.5 + data.targetX) * MageS.Game.cellSize) + 'rem',
            // 'moveTop': ((0.5 + data.targetY) * MageS.Game.cellSize) + 'rem',
            'time': 0.1,
            'beamWidth': 10,
            'segment1': ["100%", "100%"],
            'segment2': ["0%", "100%"],
            'delete':true,
            'delay': 100,
            'yesIWantToHaveBlinkBug': true,
        };
        // MageS.Game.monimations.camShake('Y', 200, 8, 300, false, {el:$('body')});
        var icon = '';
        setTimeout(function() {
            for (var i = 0; i < data.targets.length; i++) {
                icon = array_rand(icons);
                MageS.Game.spells.beam(0, 0, data.targets[i][0], data.targets[i][1], '#FFF', icon, options);
            }
        }, 300);
        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 800);
    };

    this.finishLightning = function(data) {
        var screenOptions = {color:'#000', 'delete':true, 'duration': 200, deleteDelay:300, deleteDuration:200};
        this.spells.addScreen(screenOptions);
        var icon = 'icon-bullet-lightning';
        var options = {
            'moveLeft': ((0.5 + parseInt(data.targetX)) * MageS.Game.cellSize) + 'rem',
            'moveTop': ((0.5 + parseInt(data.targetY)) * MageS.Game.cellSize) + 'rem',
            'time': 0.1,
            'beamWidth': 10,
            'segment1': ["100%", "100%"],
            'segment2': ["0%", "100%"],
            'delete':true,
            'delay': 100,
            'yesIWantToHaveBlinkBug': true,
        };
        MageS.Game.monimations.camShake('Y', 200, 8, {el:$('body'), delay:300});
        setTimeout(function() {
            MageS.Game.spells.beamStrike(5, -90, icon, '#FFF', options);
        }, 300);
        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 800);
    };

    this.finishSkyFist = function(data) {
        var targetX = data.pattern[0][0];
        var targetY = data.pattern[0][1];
        var options = {delay:0, time:300, scale:3, startRotate:180};
        var startX = targetX;
        var startY = targetY - 16;
        this.spells.moveIcon('icon-fist', 'color-white',
            startX, startY,
            targetX, targetY-1, options);
        MageS.Game.monimations.camShake('Y', 200, 8, {delay:300});
        var rand = [];

        for(var i = 0; i < 10; i++) {
            var beamOptions = {time:0.3, beamWidth: 17, 'delete':true}; // YES WE NEED IT HERE
            rand[0] = (Math.random() * 2) - 1;
            rand[1] = (Math.random() * 2) - 1;
            MageS.Game.spells.beam(startX + rand[0], startY + rand[1], targetX + rand[0], targetY + rand[1], MageS.Game.color.lightBlue, 'icon-bullet-line', beamOptions);
        }
        var beamOptions2 = {
            'moveLeft': ((0.5 + targetX) * MageS.Game.cellSize) + 'rem',
            'moveTop': ((0.5 + targetY) * MageS.Game.cellSize) + 'rem',
            'time': 0.3,
            'beamWidth': 10,
            'segment1': ["0%", "0%"],
            'segment2': ["100%", "200%"],
            'delay': 300,
            'delete':true
        };
        for(var i2 = 0 ; i2 < 10; i2++) {
            this.spells.beamStrike(2, 360 / 10 * i2, 'icon-bullet-start-spin', MageS.Game.color.lightBlue, beamOptions2);
        }
        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 800);
    };

    this.finishLightingShield = function(data) {

        var icon = 'icon-bullet-cercle';
        var options = {
            'moveLeft': ((0.5) * MageS.Game.cellSize) + 'rem',
            'moveTop': ((0.5) * MageS.Game.cellSize) + 'rem',
            'time': 0.5,
            'beamWidth': 10,
            'segment1': ["0%", "0%"],
            'segment2': ["0%", "100%"],
            'delete': true
        };
        for(var i = 0 ; i < 7; i++) {
            icon = 'icon-bullet-simple-middle-line'
            this.spells.beamStrike(1, 360 / 7 * i, icon, MageS.Game.color.lightBlue, options)
        }

        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 800);
    };

    this.finishWindSword = function(data) {
        for (var key in data.pattern) {
            var icon = 'icon-bullet-around-side-line';
            var options = {
                'moveLeft': ((0.5 + data.pattern[key][0]) * MageS.Game.cellSize) + 'rem',
                'moveTop': ((0.5 + data.pattern[key][1]) * MageS.Game.cellSize) + 'rem',
                'time': 0.3,
                'beamWidth': 10,
                'segment1': ["0%", "0%"],
                'segment2': ["100%", "150%"],
                'delete': true,
                'delay': (key * 200)
            };
            for (var i = 0; i < 7; i++) {
                this.spells.beamStrike(1.5, 360 / 7 * i, icon, MageS.Game.color.lightBlue, options)
            }
        }

        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 1200);
    };

    this.finishLootItAll = function(data) {
        if (data.targets.length == 0) {
            MageS.Game.spells.endSpellAnimation();
            return;
        }
        for (var key in data.targets) {
            var icon = 'icon-bullet-start-spin';

            for (var i = 0; i < 10; i++) {
                var options = {
                    'time': 0.7,
                    'beamWidth': 10,
                    'segment1': ["100%", "100%"],
                    'segment2': ["-2%", "0%"],
                    'delete': true,
                    'delay': (i * 50)
                };
                this.spells.beam(0,0, data.targets[key][0], data.targets[key][1], MageS.Game.color.lightBlue, icon, options)
            }
        }

        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 1200);
    };

    this.finishPush2 = function(data) {
        var icon = 'icon-bullet-sinus-2';

        for (var i = 0; i < 20; i++) {
            var options = {
                'moveLeft': ((0.5) * MageS.Game.cellSize) + 'rem',
                'moveTop': ((0.5) * MageS.Game.cellSize) + 'rem',
                'time': 0.4,
                'beamWidth': 20,
                'segment1': ["0%", "0%"],
                'segment2': ["100%", "150%"],
                'delete': true,
                'delay': (i * 5)
            };
            this.spells.beamStrike(2.5, 360 / 20 * i, icon, MageS.Game.color.lightBlue, options)
        }

        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 600);
    };

    this.finishTeslaTrap = function(data) {

        var screenOptions = {color:'#000', 'delete':true, 'duration': 200, deleteDelay:300, deleteDuration:200};
        this.spells.addScreen(screenOptions);
        var icon = 'icon-bullet-lightning';
        var options = {
            'moveLeft': ((0.5 + parseInt(data.pattern[0][0])) * MageS.Game.cellSize) + 'rem',
            'moveTop': ((0.5 + parseInt(data.pattern[0][1])) * MageS.Game.cellSize) + 'rem',
            'time': 0.1,
            'beamWidth': 10,
            'segment1': ["100%", "100%"],
            'segment2': ["0%", "100%"],
            'delete':true,
            'delay': 100,
            'yesIWantToHaveBlinkBug': true,
        };
        setTimeout(function() {
            MageS.Game.spells.beamStrike(5, -90, icon, '#FFF', options);
        }, 300);
        setTimeout(function() {
            MageS.Game.animations.singleAnimationFinished(MageS.Game.spells.isSecondPartWaiting);
        }, 300);
        setTimeout(function() {
            MageS.Game.spells.clearAnimationField();
        }, 800);
    };

    this.finishChainLighting = function(data) {

        if (data.targets.length == 0 ) {
            MageS.Game.spells.endSpellAnimation(); return;
        }
        var screenOptions = {color:'#000', 'delete':true, 'duration': 400, deleteDelay:300, deleteDuration:200};
        this.spells.addScreen(screenOptions);
        var icons = ['icon-bullet-lightning', 'icon-bullet-lightning-2'];

        // MageS.Game.monimations.camShake('Y', 200, 8, 300, false, {el:$('body')});
        var icon = '';
        var centerX = 0;
        var centerY = 0;
        setTimeout(function() {
            for (var i = 0; i < data.targets.length; i++) {
                var options = {
                    // 'moveLeft': ((0.5 + data.targetX) * MageS.Game.cellSize) + 'rem',
                    // 'moveTop': ((0.5 + data.targetY) * MageS.Game.cellSize) + 'rem',
                    'time': 0.1,
                    'beamWidth': 10,
                    'segment1': ["100%", "100%"],
                    'segment2': ["0%", "100%"],
                    'delete':true,
                    'delay': 100 + (i * 100),
                    'yesIWantToHaveBlinkBug': true,
                };
                icon = array_rand(icons);
                MageS.Game.spells.beam(centerX, centerY, data.targets[i][0], data.targets[i][1], '#FFF', icon, options);
                centerX = data.targets[i][0];
                centerY = data.targets[i][1];
            }
        }, 300);
        setTimeout(function() {
            MageS.Game.spells.endSpellAnimation();
        }, 800);
    };

   

};

