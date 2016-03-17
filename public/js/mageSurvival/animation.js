/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */

$.fn.animateRotate = function(start, angle, duration, easing, complete) {
    var args = $.speed(duration, easing, complete);
    var step = args.step;
    return this.each(function(i, e) {
        args.complete = $.proxy(args.complete, e);
        args.step = function(now) {
            $.style(e, 'transform', 'rotate(' + now + 'deg)');
            if (step) return step.apply(e, arguments);
        };

        $({deg: start}).animate({deg: angle}, args);
    });
};


MageS.Animations = function (game) {
    this.game = game;
    this.animationsInQueue = [];
    this.currentStage = '';
    this.animationsRunning = 0;

    this.stages = [];
    this.stagesDefenition = [
        'mage-action',
        'mage-action-2',
        'mage-action-effect',
        'mage-action-effect-2',
        'unit-action',
        'unit-action-2',
    ];

    this.animateEvents = function(game) {
        this.animationsInQueue = game.events;
        this.stages = this.stagesDefenition;
        this.runAnimations();
    };

    this.runAnimations = function() {

        this.runSingleStageAnimation();
    };

    this.getNextStageName = function() {
        var stage = this.stages[0];
        this.stages = this.stages.slice(1);
        return stage;
    };

    this.runSingleStageAnimation = function() {
        //if (cardId !== undefined) {
        //    var unit = this.getUnitObj(cardId);
        //    this.stopUnitAnimation(unit);
        //}
        var stage = this.getNextStageName();
        if (!stage) {
            this.game.endAction();
            return;
        }
        if (this.animationsInQueue[stage] === undefined) {
            this.runSingleStageAnimation();
            return;
        }
        this.currentStage = stage;
        var stageAnimations = this.animationsInQueue[stage];
        for (var i in stageAnimations) {
            this.animationsRunning++;
            this.selectAnimationByName(stageAnimations[i]);
        }

    };

    this.singleAnimationFinished = function() {
        this.animationsRunning--;
        if (this.animationsRunning == 0) {
            this.runSingleStageAnimation();
        }
    };

    this.selectAnimationByName = function(data) {
        switch (data.name) {
            case 'mage-move':
                this.mageMoveAnimation(data.data);
                break;
            case 'mage-rotate':
                this.mageRotateAnimation(data.data);
                break;
            case 'unit-kill':
                this.unitKillAnimation(data.data);
                break;
            case 'unit-move':
                this.unitMoveAnimation(data.data);
                break;
            case 'mage-spell-cast':
                MageS.Game.animations.singleAnimationFinished();
                break;
            case 'mage-damage':
                info('Some one dealed ' + data.data.dHealth + ' damage to you');
                $('.health-value').html(data.data.health);
                this.showDamageAnimation(data.data, false);
                break;
            case 'unit-damage':
                info('Unit got ' + data.data.value + ' damage');
                //$('.health-value').html(data.data.health);
                this.showDamageAnimation(data.data, true);
                break;
            case 'object-destroy':
                this.objectDestroyAnimation(data.data);
                break;
            case 'wait':
                this.waitAnimation(data.data);
                break;
        }
    };

    this.mageMoveAnimation = function(data) {
        var newBattleField = $('<div class="battle-field new"></div>');
        for (var y in data.map) {
            for (var x in data.map[y]) {

                var temaplate = $('#template-map-cell').html();
                Mustache.parse(temaplate);
                var rendered = Mustache.render(temaplate, {'x': x, 'y': y, 'class': data.map[y][x]});
                var obj = $(rendered);
                newBattleField.append(obj);
                obj.css({
                    'margin-left' : (x * this.game.cellSize) + 'px',
                    'margin-top' : (y * this.game.cellSize) + 'px',
                });
            }
        }
        $('.battle-border').append(newBattleField);
        for(var y in data.objects) {
            for(var x in data.objects[y]) {
                this.game.drawObject(data.objects[y][x], x, y, '.battle-field.new');
            }
        }
        for(var y in data.units) {
            for(var x in data.units[y]) {
                this.game.drawUnit(data.units[y][x], x, y, '.battle-field.new');
            }
        }
        var newX = data.mage.x;
        var newY = data.mage.y;
        var oldX = data.mage.was.x;
        var oldY = data.mage.was.y;
        var baseMargin = this.game.fieldRadius * this.game.cellSize;

        newBattleField.css({
            'margin-left': baseMargin + (newX - oldX) * this.game.cellSize + 'px',
            'margin-top': baseMargin + (newY - oldY) * this.game.cellSize + 'px',
        });
        newBattleField.animate({
            'margin-left': baseMargin + 'px',
            'margin-top': baseMargin + 'px',
        },this.game.animationTime);
        var that = this;
        $('.battle-field.current').animate({
            'margin-left': baseMargin - (newX - oldX) * this.game.cellSize + 'px',
            'margin-top': baseMargin - (newY - oldY) * this.game.cellSize + 'px',
        }, {duration: (this.game.animationTime), complete:function(){
            $('.battle-field.current').remove();
            $('.battle-field.new').removeClass('new').addClass('current');
            MageS.Game.animations.singleAnimationFinished();
        }});
    };
    this.mageRotateAnimation = function(data) {
        var d = 0;
        var oldD = 0;
        switch (data.d) {
            case 1: d = 90; break;
            case 2: d = 180; break;
            case 3: d = 270; break;
        }
        switch (data.wasD) {
            case 1: oldD = 90; break;
            case 2: oldD = 180; break;
            case 3: oldD = 270; break;
        }
        if (oldD == 270 && d == 0) {
            oldD = -90;
        }
        if (oldD == 0 && d == 270) {
            oldD = 360;
        }
        var el = $('.battle-border .mage');
        var that = this;
        el.removeClass('d-' + data.d);
        el.animateRotate(oldD, d, this.game.animationTime / 3, "swing", function(){
            $(this).addClass('d-' + data.d).data('d', data.d);

            MageS.Game.animations.singleAnimationFinished();
        });
    };
    this.unitKillAnimation = function(data) {
        $('.battle-field.current .unit.id-' + data.id).animate({
            'opacity' : 0
        }, {
            duration:1000,
            'complete': (function () {
                $(this).remove();
                MageS.Game.animations.singleAnimationFinished();
            }
        )});
    };
    this.unitMoveAnimation = function(data) {
        var unit = $('.battle-field.current .unit.id-' + data.id);
        if (unit.length < 1) {
            info('unit with ID = ' + data.id + ' was not on the map');
            // ok we don't have that unit at all.
            var unit = this.game.drawUnit(data.data, data.oldX, data.oldY);
            var oldX = data.oldX;
            var oldY = data.oldY;
        } else {
            var oldCell = unit.parent('.cell');
            var oldX = oldCell.data('x');
            var oldY = oldCell.data('y');
        }

        var cellToGo = $('.battle-field.current .cell.x-' + data.x + '.y-' + data.y);
        $('.unit-field').append(unit);
        unit.css({
            'margin-left': oldX * this.game.cellSize + 'px',
            'margin-top': oldY * this.game.cellSize + 'px'
        });
        unit.animate({
            'margin-left' : data.x * this.game.cellSize + 'px',
            'margin-top' : data.y * this.game.cellSize + 'px'
        }, {
            duration:this.animationTime,
            'complete': (function () {
                    if (cellToGo.length < 1) {
                        $(this).remove();
                        MageS.Game.animations.singleAnimationFinished();
                        return;
                    }
                    $(this).css({
                        'margin-left' : '0px',
                        'margin-top' : '0px'
                    });
                    cellToGo.append($(this));

                MageS.Game.animations.singleAnimationFinished();
            }
        )});
    };

    this.showDamageAnimation = function (data, enemy) {
        var id = data.id;
        if (enemy) {
            var target = $('.unit.id-' + id);
        } else {
            var target = $('.battle-border .mage-container');
        }
        info(data);
        var el = $('<div>' + (-data.value) + '</div>').addClass('damage');
        target.append(el);
        el.css({'margin-top':'10px','margin-left':'10px',})
            .animate(
                {'margin-top':'0','margin-left':'0',},
                {duration:300, complete:function() {
            $(this).remove();
            MageS.Game.animations.singleAnimationFinished();
        }})
    };

    this.waitAnimation = function(data)
    {
        setTimeout(function() {
            MageS.Game.animations.singleAnimationFinished();
        }, data.time);
    };

    this.objectDestroyAnimation = function(data)
    {
        var el = $('.object.id-' + data.id);
        el.animate({opacity:0},{'duration':300, 'complete':function(){
            $(this).remove();
            MageS.Game.animations.singleAnimationFinished();
        }});
    };
};

