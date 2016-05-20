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
        'mage-action-3',
        'mage-action-effect',
        'mage-action-effect-2',
        'unit-action',
        'unit-action-2',
        'unit-action-3',
        'turn-end-effects',
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
        if (this.animationsRunning <= 0) {
            if (this.animationsRunning < 0) {
                info('Error. More animations finished then started');
            }
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
            case 'unit-rotate':
                this.unitRotateAnimation(data.data);
                break;
            case 'unit-attack':
                this.unitAttackAnimation(data.data);
                break;
            case 'unit-remove-status':
                this.unitRemoveStatusAnimation(data.data);
                break;
            case 'mage-spell-cast':
                this.spellCastAnimation(data.data);
                break;
            case 'spell-craft':
                this.game.spellcraft.endSpellCraftAnimations(data.data);
                break;
            case 'mage-damage':
                this.mageDamageAnimation(data.data);
                break;
            case 'mage-heal':
                this.mageHealAnimation(data.data);
                break;
            case 'mage-add-armor':
                this.mageAddArmorAnimation(data.data);
                break;
            case 'mage-use-portal':
                this.mageUsePortalAnimation(data.data);
                break;
            case 'unit-damage':
                info('Unit got ' + data.data.value + ' damage');
                //$('.health-value').html(data.data.health);
                this.showDamageAnimation(data.data, 'damage', true);
                break;
            case 'object-destroy':
                this.objectDestroyAnimation(data.data);
                break;
            case 'object-activate':
                this.game.objects.activate(data.data);
                break;
            case 'add-object':
                this.addObjectAnimation(data.data);
                break;
            case 'add-unit':
                this.addUnitAnimation(data.data);
                break;
            case 'add-unit-status':
                this.addUnitStatusAnimation(data.data);
                break;
            case 'cell-change':
                this.changeCellAnimation(data.data);
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
                this.game.drawCell(data.map[y][x], x, y, newBattleField);
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
            MageS.Game.animations.singleAnimationFinished();
        }});
    };
    this.mageRotateAnimation = function(data) {
        var el = $('.battle-border .mage');
        this.rotate(el, data);
    };
    this.rotate = function(el, data) {
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

        el.removeClass('d-' + data.d);
        info('oldD=' + oldD);
        info('d=' + d);
        el.animateRotate(oldD, d, this.game.animationTime * 3, "swing", function(){
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
            var unit = this.game.drawUnit(data, data.oldX, data.oldY);
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
                        $(this).remove();
                        MageS.Game.animations.singleAnimationFinished();
                        return;
                    }
                    $(this).css({
                        'margin-left' : '0',
                        'margin-top' : '0'
                    });
                    cellToGo.append($(this));

                MageS.Game.animations.singleAnimationFinished();
            }
        )});
    };
    this.unitRotateAnimation = function(data) {
        var unit = $('.battle-field.current .unit.id-' + data.id);
        this.rotate(unit, data);
    };
    this.unitAttackAnimation = function(data) {

        this.game.attacks.attack(data);

        MageS.Game.animations.singleAnimationFinished();
     
    };
    this.unitRemoveStatusAnimation = function(data) {

        var unit = $('.battle-border .unit.id-' + data.id);
        unit.find('.unit-status.flag-' + data.flag).remove();

        MageS.Game.animations.singleAnimationFinished();
     
    };
    this.spellCastAnimation = function(data) {
        this.game.spells.cast(data);
    };
    this.mageDamageAnimation = function(data) {
        info('Some one dealed ' + data.value + ' damage to you');
        //$('.health-value').html(data.health);
        this.game.updateHealth(data);
        //$('.health-bar .progress-bar-success').css('width', data.health + '%');
        this.showDamageAnimation(data, 'damage', false);
    };
    this.mageHealAnimation = function(data) {
        info('Healing for ' + data.value);
        //$('.health-value').html(data.health);
        this.game.updateHealth(data);
        //$('.health-bar .progress-bar-success').css('width', data.health + '%');
        this.showDamageAnimation(data, 'heal', false);
    };
    this.mageAddArmorAnimation = function(data) {
        info('Adding armor ' + data.value);
        //$('.health-value').html(data.health);
        this.game.updateHealth(data);
        //$('.health-bar .progress-bar-success').css('width', data.health + '%');
        this.showDamageAnimation(data, 'armor', false);
    };
    this.mageUsePortalAnimation = function(data) {
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

    this.showDamageAnimation = function (data, type, enemy) {
        var id = data.id;
        if (enemy) {
            var target = $('.unit.id-' + id);
        } else {
            var target = $('.battle-border .mage-container');
        }
        if (type == 'damage') {
            var value = -data.value;
        } else {
            var value = data.value;
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

    this.addObjectAnimation = function(data)
    {
        var newObject = this.game.drawObject(data.object, data.object.x, data.object.y);
        MageS.Game.animations.singleAnimationFinished();
    };

    this.addUnitAnimation = function(data)
    {
        var newUnit = this.game.drawUnit(data.unit, data.targetX, data.targetY);
        MageS.Game.animations.singleAnimationFinished();
    };
    this.addUnitStatusAnimation = function(data)
    {
        var unit = $('.battle-border .unit.id-' + data.id);
        // var icon = this.spells.createIcon('icon-cracked-glass', 'color-blue-bright').addClass('unit-status');
        // unit.prepend(icon);
        // icon.css({opacity:0});
        // icon.animate({'opacity':0.7}, {
        //     step: function(now,fx) {
        //         $(this).css('-webkit-transform','scale(' + (1.7 - now) + ')');
        //     },duration:600});
        this.game.addUnitStatusIcons(unit, data.flags);

        setTimeout(function () {
            MageS.Game.animations.singleAnimationFinished();
        }, 600)
    };
    this.changeCellAnimation = function(data)
    {
        // var newObject = this.game.drawObject(data.object, data.object.x, data.object.y);
        var cell = $('.battle-border .cell.x-' + data.targetX + '.y-' + data.targetY);
        info(cell);
        var svgs = cell.find('.svg');
        if (svgs.length > 0) {
            svgs.remove();
        }
        var currentType = cell.data('class');
        cell.removeClass(currentType).addClass(data.cell).data('class', data.cell);
        this.game.worlds.cell(this.game.worldType, data.cell, cell);
        MageS.Game.animations.singleAnimationFinished();
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

