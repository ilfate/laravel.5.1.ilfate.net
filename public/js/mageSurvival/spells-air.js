/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Spells.Air = function (game, spells) {
    this.game = game;
    this.spells = spells;

    this.startStandartAir = function()
    {
        info('Standart Air start');
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

    this.iterateStandartAir = function() {
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

    this.standartAirFinish = function(duration) {
        if (!duration) {
            duration = 300;
        }
        for (var i = 0; i < 4; i++) {
            var svg = this.spells.savedData[i];
            this.game.monimations.rotate(svg, 0, 360, 300, false, false);
            svg.animate({'margin':0}, {'duration':duration, complete:function() {
                $(this).animate({opacity:0}, {'duration':100, complete:function() {
                    $(this).remove();
                }});
            }
            });
        }
    };

    this.finishPush = function(data) {
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

   

};

