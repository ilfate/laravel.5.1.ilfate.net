/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Spells.Arcane = function (game, spells) {
    this.game = game;
    this.spells = spells;

    this.startStandartArcane = function()
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

    this.iterateStandartArcane = function() {
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

    

   

};

