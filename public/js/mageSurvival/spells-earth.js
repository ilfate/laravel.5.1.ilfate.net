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
                //'icon-bullet-simple-middle-line'
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
                preAnimationDelay: 200,
                // rangeRandom:2,
                // rangeMove: 1
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
            // 'delete': true
        };
        for(var i = 0 ; i < 4; i++) {
            //'icon-bullet-simple-middle-line'
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


   

};

