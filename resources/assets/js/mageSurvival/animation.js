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
    this.isAnimationsRunning = false;

    this.stages = [];
    this.stagesDefenition = [
        'mage-before-action-speech',
        'mage-action',
        'mage-action-2',
        'mage-action-3',
        'mage-after-action-speech',
        'mage-action-effect',
        'mage-action-effect-2',
        'unit-action',
        'unit-action-2',
        'unit-action-3',
        'turn-end-effects',
        'turn-end-effects-2',
        'message-time',
        'message-time-2',
        'message-time-3',
    ];

    this.animateEvents = function(game) {
        this.animationsInQueue = game.events;
        this.stages = this.stagesDefenition;
        this.isAnimationsRunning = true;
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
            this.isAnimationsRunning = false;
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
            case 'mage-spell-cast':
                this.spellCastAnimation(data.data, stage);
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
            case 'mage-damage':
                this.mageDamageAnimation(data.data, stage);
                break;
            case 'mage-heal':
                this.mageHealAnimation(data.data, stage);
                break;
            case 'mage-use-portal':
                this.mageUsePortalAnimation(data.data, stage);
                break;
            case 'mage-death':
                this.mageUsePortalAnimation(data.data, stage);
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
            case 'unit-spawn':
                this.unitSpawnAnimation(data.data, stage);
                break;
            case 'spell-craft':
                this.game.spellcraft.endSpellCraftAnimations(data.data, stage);
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
            case 'say-message':
                this.sayMessageAnimation(data.data, stage);
                break;
            case 'user-ask-to-register':
                this.askRegistrationAnimation(data.data, stage);
                break;
            case 'effect':
                this.effectAnimation(data.data, stage);
                break;
            case 'wait':
                this.waitAnimation(data.data, stage);
                break;
        }
    };

    this.mageMoveAnimation = function(data, stage) {
        this.game.mage.moveMage(data, stage);
    };
    
    this.mageRotateAnimation = function(data, stage) {
        var el = $('.battle-border .mage');
        var currentD = el.data('d');
        if (currentD == data.d) {
            MageS.Game.animations.singleAnimationFinished(stage);
            return;
        }
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
        el.removeClass('d-' + data.wasD);
        el.animateRotate(oldD, d, 150, "swing", function(){
            $(this).addClass('d-' + data.d).data('d', data.d);
            if (stage) {
                MageS.Game.animations.singleAnimationFinished(stage);
            }
        });
    };
    this.unitKillAnimation = function(data, stage) {
        var unit = $('.battle-border .unit.id-' + data.id);
        if (unit.length < 1) {
            MageS.Game.animations.singleAnimationFinished(stage);
            return;
        }
        this.game.units.animateDeath(unit, stage);
    };
    
    this.unitMoveAnimation = function(data, stage) {
        this.game.units.moveUnit(data, stage);
    };
    
    this.unitRotateAnimation = function(data, stage) {
        var unit = $('.battle-border .unit.id-' + data.id + ' .rotate-div');

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
    this.unitSpawnAnimation = function(data, stage) {

        this.game.units.unitSpawn(data, stage);

        
     
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
        this.game.mage.addMageStatus(data.flags);

        setTimeout(function () {
            MageS.Game.animations.singleAnimationFinished(stage);
        }, 600)
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
        this.game.monimations.rotateWithScale($('body'), 0, 540, 1, -1, 1500);
        setTimeout(function(){
            if (MageS.Game.admin.isEnabled) {
                window.location = '/Spellcraft/admin';
            } else {
                window.location = '/Spellcraft';
            }
        }, 750);
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
            target = $('.battle-border .mage-damage-container');
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

    this.askRegistrationAnimation = function(data, stage)
    {
        setTimeout(function() {
            MageS.Game.registrationPopup();
        }, data.time);


        setTimeout(function() {
            MageS.Game.animations.singleAnimationFinished(stage);
        }, data.time+ 2000);
    };

    this.effectAnimation = function(data, stage)
    {
        this.game.spells.isSecondPartWaiting = stage;
        this.game.spells.continue(data.spell, data)
    };

    this.waitAnimation = function(data, stage)
    {
        setTimeout(function() {
            MageS.Game.animations.singleAnimationFinished(stage);
        }, data.time);
    };

    this.sayMessageAnimation = function(data, stage)
    {
        this.game.chat.dialogMessage(data, stage);
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
    
    this.changeCellAnimation = function(data, stage)
    {
        // var newObject = this.game.drawObject(data.object, data.object.x, data.object.y);
        this.game.worlds.cellsChange(data, stage);
    };
    
    
};

