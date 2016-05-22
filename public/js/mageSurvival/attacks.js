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

    this.attack = function(data) {
        var attackId = Math.random() * 10000;
        if (this.attacks[attackId] !== undefined) {
            this.attack(data);
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
                this.game.units.meleeAttack(data, container, attackId);
                break;
            case 'fireSpit':
                this.fireSpit(attackId);
                break;
            case 'web':
                this.web(attackId);
                break;
            default:
                info('there is no attack animation for ' + data.attack.animation);
                MageS.Game.attacks.finishAttack(attackId);
                break;
        }
    };

    this.fireSpit = function(id) {
        var data = this.attacks[id].data;
        MageS.Game.spells.beam(data.fromX, data.fromY, data.targetX, data.targetY, '#F07818');
        MageS.Game.spells.beam(data.fromX, data.fromY, data.targetX, data.targetY, '#F07818', 'icon-bullet-line-small-curve-right');
        MageS.Game.spells.beam(data.fromX, data.fromY, data.targetX, data.targetY, '#F07818', 'icon-bullet-line-small-curve-left');

        setTimeout(function() {
            MageS.Game.attacks.finishAttack(id);
        }, 800);
    };

    this.web = function(id) {
        var data = this.attacks[id].data;
        var options = {time:700};
        MageS.Game.spells.moveIcon('icon-spider-web', 'color-white', data.fromX, data.fromY, data.targetX, data.targetY, options);


        setTimeout(function() {
            MageS.Game.attacks.finishAttack(id);
        }, 800);
    };
    
    this.finishAttack = function (id) {
        this.clearAttack(id);
        MageS.Game.animations.singleAnimationFinished();
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

