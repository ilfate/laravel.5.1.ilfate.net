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
    this.animationsRunning = [];

    this.stages = [];
    this.stagesDefenition = [
        'mage-action',
        'mage-action-2',
        'mage-action-3',
        'mage-action-effect',
        'mage-action-effect-2',
        'unit-action',
        'unit-action-2',
        'unit-action-3',
        'turn-end-effects',
        'turn-end-effects-2',
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
        this.animationsRunning[stage] = stageAnimations.length;
        for (var i in stageAnimations) {
            this.selectAnimationByName(stageAnimations[i], stage);
        }

    };

    this.singleAnimationFinished = function(stage) {
        this.animationsRunning[stage]--;
        if (this.animationsRunning[stage] == 0) {
            this.runSingleStageAnimation();
        } else if (this.animationsRunning[stage] < 0) {
            info('Error. More animations finished then started');
        }
    };

    this.selectAnimationByName = function(data, stage) {
        switch (data.name) {
            case 'mage-move':
                this.mageMoveAnimation(data.data, stage);
                break;
            case 'mage-rotate':
                this.mageRotateAnimation(data.data, stage);
                break;
            case 'unit-kill':
                this.unitKillAnimation(data.data, stage);
                break;
            case 'unit-move':
                this.unitMoveAnimation(data.data, stage);
                break;
            case 'unit-rotate':
                this.unitRotateAnimation(data.data, stage);
                break;
            case 'unit-attack':
                this.unitAttackAnimation(data.data, stage);
                break;
            case 'unit-remove-status':
                this.unitRemoveStatusAnimation(data.data, stage);
                break;
            case 'mage-spell-cast':
                this.spellCastAnimation(data.data, stage);
                break;
            case 'spell-craft':
                this.game.spellcraft.endSpellCraftAnimations(data.data, stage);
                break;
            case 'mage-damage':
                this.mageDamageAnimation(data.data, stage);
                break;
            case 'mage-heal':
                this.mageHealAnimation(data.data, stage);
                break;
            case 'mage-add-armor':
                this.mageAddArmorAnimation(data.data, stage);
                break;
            case 'mage-add-status':
                this.mageAddStatusAnimation(data.data, stage);
                break;
            case 'mage-remove-status':
                this.mageRemoveStatusAnimation(data.data, stage);
                break;
            case 'mage-use-portal':
                this.mageUsePortalAnimation(data.data, stage);
                break;
            case 'unit-damage':
                info('Unit got ' + data.data.value + ' damage');
                //$('.health-value').html(data.data.health);
                this.showDamageAnimation(data.data, 'damage', true, stage);
                break;
            case 'object-destroy':
                this.objectDestroyAnimation(data.data, stage);
                break;
            case 'object-activate':
                this.game.objects.activate(data.data, stage);
                break;
            case 'object-move':
                this.game.objects.move(data.data, stage);
                break;
            case 'add-object':
                this.addObjectAnimation(data.data, stage);
                break;
            case 'add-unit':
                this.addUnitAnimation(data.data, stage);
                break;
            case 'add-unit-status':
                this.addUnitStatusAnimation(data.data, stage);
                break;
            case 'cell-change':
                this.changeCellAnimation(data.data, stage);
                break;
            case 'wait':
                this.waitAnimation(data.data, stage);
                break;
        }
    };

    this.mageMoveAnimation = function(data, stage) {
        var newBattleField = $('<div class="battle-field new"></div>');
        for (var y in data.map) {
            for (var x in data.map[y]) {
                this.game.drawCell(data.map[y][x], x, y, newBattleField);
            }
        }
        $('.battle-border').append(newBattleField);
        for(var y in data.objects) {
            for(var x in data.objects[y]) {
                this.game.objects.drawObject(data.objects[y][x], x, y, '.battle-field.new');
            }
        }
        $('.tooltip-unit-area .unit-tooltip').remove();
        for(var y in data.units) {
            for(var x in data.units[y]) {
                this.game.units.drawUnit(data.units[y][x], x, y, '.battle-field.new');
            }
        }
        var newX = data.mage.x;
        var newY = data.mage.y;
        var oldX = data.mage.was.x;
        var oldY = data.mage.was.y;
        var baseMargin = this.game.fieldRadius * this.game.cellSize;

        newBattleField.css({
            'margin-left': baseMargin + (newX - oldX) * this.game.cellSize + 'rem',
            'margin-top': baseMargin + (newY - oldY) * this.game.cellSize + 'rem'
        });
        var animateTime = this.game.animationTime;
        var dX = Math.abs(newX - oldX);
        var dY = Math.abs(newY - oldY);
        var dSum = dX + dY;
        if (dSum > 1) { animateTime = animateTime * ( (dX + dY) / 2 + 0.5 ); }
        if (!this.game.spells.spellAnimationRunning) {
            this.mageMoveHands(animateTime);
        }
        this.rotateTorso(animateTime, 2);
        newBattleField.animate({
            'margin-left': baseMargin + 'rem',
            'margin-top': baseMargin + 'rem'
        }, {'duration': animateTime});
        var that = this;
        $('.battle-field.current').animate({
            'margin-left': baseMargin - (newX - oldX) * this.game.cellSize + 'rem',
            'margin-top': baseMargin - (newY - oldY) * this.game.cellSize + 'rem'
        }, {duration: animateTime,
            complete:function(){
            $('.battle-field.current').remove();
            $('.battle-field.new').removeClass('new').addClass('current');
            MageS.Game.animations.singleAnimationFinished(stage);
        }});
    };
    this.mageRotateAnimation = function(data, stage) {
        var el = $('.battle-border .mage');
        this.rotate(el, data, stage);
    };
    this.rotate = function(el, data, stage) {
        var d = data.d * 90;
        var oldD = data.wasD * 90;
        if (oldD == 270 && d == 0) {
            oldD = -90;
        }
        if (oldD == 0 && d == 270) {
            oldD = 360;
        }

        el.removeClass('d-' + data.d);
        el.animateRotate(oldD, d, this.game.animationTime / 3, "swing", function(){
            $(this).addClass('d-' + data.d).data('d', data.d);

            MageS.Game.animations.singleAnimationFinished(stage);
        });
    };
    this.unitKillAnimation = function(data, stage) {
        var unit = $('.battle-field.current .unit.id-' + data.id);
        if (unit.length < 1) {
            MageS.Game.animations.singleAnimationFinished(stage);
            return;
        }
        this.game.units.animateDeath(unit, stage);
    };
    this.unitMoveAnimation = function(data, stage) {
        var unit = $('.battle-field.current .unit.id-' + data.id);
        if (unit.length < 1) {
            info('unit with ID = ' + data.id + ' was not on the map');
            info(data);
            info(this.currentStage);
            var unit2 =  $('.unit.id-' + data.id);
            if (unit2.length) {
                info('Animation unit is there... but not at right place...');
                info(unit2);
            }
            // ok we don't have that unit at all.
            var unit = this.game.units.drawUnit(data.data, data.oldX, data.oldY);
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
            'margin-left': oldX * this.game.cellSize + 'rem',
            'margin-top': oldY * this.game.cellSize + 'rem'
        });
        unit.animate({
            'margin-left' : data.x * this.game.cellSize + 'rem',
            'margin-top' : data.y * this.game.cellSize + 'rem'
        }, {
            duration:this.animationTime,
            'complete': (function () {
                if (cellToGo.length < 1) {
                    MageS.Game.animations.singleAnimationFinished(stage);
                    $(this).remove();
                    return;
                }
                $(this).css({
                    'margin-left' : '0',
                    'margin-top' : '0'
                });
                cellToGo.append($(this));

                MageS.Game.animations.singleAnimationFinished(stage);
            }
        )});
        this.game.units.animateMove(unit);
    };
    this.unitRotateAnimation = function(data, stage) {
        var unit = $('.battle-field.current .unit.id-' + data.id + ' .rotate-div');

        if (unit.length > 0) {
            this.rotate(unit, data, stage);
        } else {
            info('unit for rotate with id = ' + data.id + ' was not found');
            var unit2 = $('.unit-field .unit.id-' + data.id + ' .rotate-div');
            if (unit2.length > 0) {
                info('ANIMATION ORDER IS FUCKED UP!!!!!!')
            }
            MageS.Game.animations.singleAnimationFinished(stage);
        }
    };
    this.unitAttackAnimation = function(data, stage) {

        this.game.attacks.attack(data, stage);
    };
    this.unitRemoveStatusAnimation = function(data, stage) {

        var unitFlag = $('.battle-border .unit.id-' + data.id + ' .unit-status.flag-' + data.flag);
        if (unitFlag.length > 0) {
            unitFlag.remove();
        }

        MageS.Game.animations.singleAnimationFinished(stage);
     
    };
    this.mageRemoveStatusAnimation = function(data, stage) {

        var mage = $('.battle-border .mage');
        mage.find('.unit-status.flag-' + data.flag).remove();

        MageS.Game.animations.singleAnimationFinished(stage);
     
    };
    this.spellCastAnimation = function(data, stage) {
        this.game.spells.cast(data, stage);
    };
    this.mageDamageAnimation = function(data, stage) {
        info('Some one dealed ' + data.value + ' damage to you');
        //$('.health-value').html(data.health);
        this.game.updateHealth(data);
        //$('.health-bar .progress-bar-success').css('width', data.health + '%');
        this.showDamageAnimation(data, 'damage', false, stage);
    };
    this.mageHealAnimation = function(data, stage) {
        info('Healing for ' + data.value);
        //$('.health-value').html(data.health);
        this.game.updateHealth(data);
        //$('.health-bar .progress-bar-success').css('width', data.health + '%');
        this.showDamageAnimation(data, 'heal', false, stage);
    };
    this.mageAddArmorAnimation = function(data, stage) {
        info('Adding armor ' + data.value);
        //$('.health-value').html(data.health);
        this.game.updateHealth(data);
        //$('.health-bar .progress-bar-success').css('width', data.health + '%');
        this.showDamageAnimation(data, 'armor', false, stage);
    };
    this.mageUsePortalAnimation = function(data, stage) {
        info('PORTAL');
        //$('.battle-field.current .cell').css('position', 'fixed').each(function() {
        //    //var thisTop = parseInt($(this).offset().top);
        //    //var thisLeft = parseInt($(this).offset().left);
        //    //info(thisTop);
        //    //$(this).css({'top' : thisTop, 'left': thisLeft})
        //    $(this).animate({
        //        'margin-top': Math.random() * 500
        //    }, {'easing': 'easeOutBack'});
        //    $(this).animate({
        //        'margin-left': Math.random() * 500
        //    }, {'easing': 'easeInBack'})
        //});
        this.game.monimations.rotateWithScale($('body'), 0, 540, 1, -1, 1000);
        setTimeout(function(){
            window.location = '/Spellcraft';
        }, 500);
            //.animate()
    };

    this.showDamageAnimation = function (data, type, enemy, stage) {
        var id = data.id; var target = {};
        if (type == 'damage') {
            var value = -data.value;
        } else {
            var value = data.value;
        }
        if (enemy) {
            target = $('.unit.id-' + id);
            if (target.length < 1) {
                MageS.Game.animations.singleAnimationFinished(stage);
                return;
            }
            var unitTooltip = $('.tooltip-unit-area .unit-tooltip.id-' + data.id);
            if (unitTooltip.length > 0) {
                unitTooltip.find('.current-health').html(data.health);
            }
        } else {
            target = $('.battle-border .mage-container');
        }
        
        var el = $('<div>' + value + '</div>').addClass('damage');
        if (type == 'heal') {
            el.addClass('heal');
        } if(type == 'armor') {
            el.addClass('armor');
        }
        target.prepend(el);
        var distanceInRem = 0.75;
        if (rand(0,1) == 1) {
            var randX = distanceInRem * 100;
            var randY = rand(0, distanceInRem * 100);
        } else {
            var randX = rand(0, distanceInRem * 100);
            var randY = distanceInRem * 100;
        }
        randX = randX / 100;
        randY = randY / 100;
        var y = parseInt(el.css('margin-top'))/20;
        var x = parseInt(el.css('margin-left'))/20;
        if (rand(0,1) == 1) {
            y += randY;
        } else {
            y -= randY;
        }
        if (rand(0,1) == 1) {
            x += randX;
        } else {
            x -= randX;
        }
        el.animate(
                {'margin-top':y + 'rem','margin-left':x + 'rem', opacity: 0.3},
                {duration:400, complete:function() {
            $(this).remove();
        }});
        setTimeout(function () {
            MageS.Game.animations.singleAnimationFinished(stage);
        }, 400);
    };

    this.waitAnimation = function(data, stage)
    {
        setTimeout(function() {
            MageS.Game.animations.singleAnimationFinished(stage);
        }, data.time);
    };

    this.objectDestroyAnimation = function(data, stage)
    {
        var el = $('.object.id-' + data.id);
        if (el.length > 0) {
            el.animate({opacity:0},{'duration':300, 'complete':function(){
                $(this).remove();
            }});
        }
        setTimeout(function () {
            MageS.Game.animations.singleAnimationFinished(stage);
        }, 300);
    };

    this.addObjectAnimation = function(data, stage)
    {
        var newObject = this.game.objects.drawObject(data.object, data.object.x, data.object.y);
        MageS.Game.animations.singleAnimationFinished(stage);
    };

    this.addUnitAnimation = function(data, stage)
    {
        info(data.unit);
        var newUnit = this.game.units.drawUnit(data.unit, data.targetX, data.targetY);
        MageS.Game.animations.singleAnimationFinished(stage);
    };
    this.addUnitStatusAnimation = function(data, stage)
    {
        var unit = $('.battle-border .unit.id-' + data.id);
        if (unit.length > 0) {
            this.game.units.addUnitStatusIcons(unit, data.flags);
        }

        setTimeout(function () {
            MageS.Game.animations.singleAnimationFinished(stage);
        }, 600);
    };
    this.mageAddStatusAnimation = function(data, stage)
    {
        var mage = $('.battle-border .mage');
        this.game.addMageStatus(data.flags);

        setTimeout(function () {
            MageS.Game.animations.singleAnimationFinished(stage);
        }, 600)
    };
    this.changeCellAnimation = function(data, stage)
    {
        // var newObject = this.game.drawObject(data.object, data.object.x, data.object.y);
        var cell = $('.battle-border .cell.x-' + data.targetX + '.y-' + data.targetY);
        var svgs = cell.find('.svg');
        if (svgs.length > 0) {
            svgs.remove();
        }
        var currentType = cell.data('class');
        cell.removeClass(currentType).addClass(data.cell).data('class', data.cell);
        this.game.worlds.cell(this.game.worldType, data.cell, cell);
        MageS.Game.animations.singleAnimationFinished(stage);
    };
    
    this.mageMoveHands = function(duration) {
        var mageSvg = $('.battle-border .mage svg');
        var leftHand = mageSvg.find('.mage-hand-left');
        var leftHandFist = mageSvg.find('.mage-hand-left-fist');
        var rightHand = mageSvg.find('.mage-hand-right');
        var rightHandFist = mageSvg.find('.mage-hand-right-fist');
        var left = [leftHand, leftHandFist];
        var right = [rightHand, rightHandFist];
        var num = Math.floor(duration / 100);

        for (var i = 0; i < num; i++) {
            this.handSwitch(i * 100, (i%2==1) ? left : right);
        }
        setTimeout(function(){
            $('.battle-border .mage path.hand').show();
        }, duration);
    };
    
    this.handSwitch = function(delay, toHide) {
        setTimeout(function() {
            $('.battle-border .mage path.hand').show();
            for (var n in toHide) {
                toHide[n].hide();
            }
        }, delay);
    };

    this.rotateTorso = function(duration, amplitude) {
        var num = Math.floor(duration / 100);
        for (var i = 0; i < num; i++) {
            this.rotateTorsoSingle(i * 100, amplitude, (i%2==1) ? -1 : 1);
        }
        setTimeout(function(){
            $('.battle-border .mage path.mage-torso')[0].style.transform = 'rotate(0)';
        }, duration);
    };
    this.rotateTorsoSingle = function(delay, amplitude, direction) {
        setTimeout(function() {
            $('.battle-border .mage path.mage-torso')[0].style.transform = 'rotate(' + (amplitude * direction) + 'deg)';
        }, delay);
    };
};

