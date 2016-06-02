/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Attacks = function (game) {
    this.game = game;
    this.field = {};
    this.attacks = {};

    this.init = function () {
        this.field = $('.attacks-field');
    };

    this.attack = function(data, stage) {
        var attackId = Math.random() * 10000;
        if (this.attacks[attackId] !== undefined) {
            this.attack(data, stage);
            info('Restart attack');
            return;
        }
        var container = $('<div></div>').addClass('id-' + attackId);
        this.attacks[attackId] = {
            'data': data,
            'container': container
        };
        $('.attacks-field').append(container);
        switch (data.attack.animation) {
            case 'melee':
                this.game.units.meleeAttack(data, container, attackId, stage);
                break;
            case 'fireSpit':
                this.fireSpit(attackId, stage);
                break;
            case 'web':
                this.web(attackId, stage);
                break;
            case 'spawn':
                this.spawn(attackId, stage);
                break;
            case 'greenLaser':
                this.greenLaser(attackId, stage);
                break;
            default:
                info('there is no attack animation for ' + data.attack.animation);
                MageS.Game.attacks.finishAttack(attackId, stage);
                break;
        }
    };

    this.fireSpit = function(id, stage) {
        var data = this.attacks[id].data;
        MageS.Game.spells.beam(data.fromX, data.fromY, data.targetX, data.targetY, '#F07818');
        MageS.Game.spells.beam(data.fromX, data.fromY, data.targetX, data.targetY, '#F07818', 'icon-bullet-line-small-curve-right');
        MageS.Game.spells.beam(data.fromX, data.fromY, data.targetX, data.targetY, '#F07818', 'icon-bullet-line-small-curve-left');

        setTimeout(function() {
            MageS.Game.attacks.finishAttack(id, stage);
        }, 800);
    };

    this.web = function(id, stage) {
        var data = this.attacks[id].data;
        var options = {time:700, rotate:true};
        MageS.Game.spells.moveIcon('icon-spider-web', 'color-white', data.fromX, data.fromY, data.targetX, data.targetY, options);


        setTimeout(function() {
            MageS.Game.attacks.finishAttack(id, stage);
        }, 800);
    };

    this.spawn = function(id, stage) {
        var data = this.attacks[id].data;
        //var options = {time:700, rotate:true};
        for (var n in data.targets) {
            var options = {
                'moveLeft': ((data.targets[n][0] + 0.5) * MageS.Game.cellSize) + 'rem',
                'moveTop': ((data.targets[n][1] + 0.5) * MageS.Game.cellSize) + 'rem',
                'time': 1,
                'beamWidth': 10,
                'segment1': ["100%", "100%"],
                'segment2': ["-8%", "0"],
                'delete':true
            };
            for (var i = 0; i < 5; i++) {
                //'icon-bullet-simple-middle-line'
                MageS.Game.spells.beamStrike(4, 360 / 5 * i, 'icon-bullet-start-spin', '#07B26A', options)
            }
        }

        setTimeout(function() {
            MageS.Game.attacks.finishAttack(id, stage);
        }, 800);
    };

    this.greenLaser = function(id, stage) {
        var data = this.attacks[id].data;
        var options = {
            'time': 0.2,
            'beamWidth': 10,
            'segment1': ["0%", "0%"],
            'segment2': ["0%", "100%"],
        };
        var unit = $('.battle-border .cell.x-' + data.fromX +'.y-' + data.fromY + ' .unit');
        MageS.Game.units.rotateUnitToTarget(unit, data);
        var beam = MageS.Game.spells.beam(data.fromX, data.fromY, data.targetX, data.targetY, '#07B26A', 'icon-bullet-line',  options);

        var options2 = {time:300};
        var x = 0;
        var y = 0;
        for(var i = 0; i < 30; i ++) {
            x = data.targetX + (Math.random() * 2) - 1;
            y = data.targetY + (Math.random() * 2) - 1;
            options2.delay = 200 + (Math.random() * 800);
            options2.scale = 0.1;
            MageS.Game.spells.moveIcon('icon-cercle', 'color-green', data.targetX, data.targetY, x, y, options2);
        }

        setTimeout(function() {
            MageS.Game.units.rotateUnitBack(unit);
            beam.remove();
            MageS.Game.attacks.finishAttack(id, stage);
        }, 1200);
    };
    
    this.finishAttack = function (id, stage) {
        this.clearAttack(id);
        MageS.Game.animations.singleAnimationFinished(stage);
    };
    
    this.clearAttack = function (id) {
        if (this.attacks[id] === undefined) {
            info('Wtf I cant clear attack with id = ' + id);
            return;
        }
        this.attacks[id].container.remove();
        delete this.attacks[id];
    };
};

