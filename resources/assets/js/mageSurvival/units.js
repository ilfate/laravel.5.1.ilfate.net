/**
 * Created by Ilya Rubinchik (ilfate) on 08/04/16.
 */




MageS.Units = function (game) {
    this.game = game;

    this.drawUnit = function(unit, x, y, target) {
        // if (!target) {
        //     target = '.battle-field.current';
        // }
        var temaplate = $('#template-unit').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'id': unit.id, 'type': unit.type});
        var obj = $(rendered);
        var icons = this.game.getIcon(unit.icon);
        obj.data('icon', unit.icon);
        obj.find('svg').append(icons.clone());
        if (unit.iconColor !== undefined) { obj.find('.svg').addClass(unit.iconColor); }
        //
        if (!target) {
            this.unitToUnitField(obj, x, y);
        } else {
            obj.data('x', x);
            obj.data('y', y);
            this.unitToCell(obj, target);
        }
        if (unit.d !== undefined) {
            if ( unit.d > 0) {
                obj.find('.rotate-div')[0].style.transform = 'rotate(' + (unit.d * 90) + 'deg)';
            }
            obj.data('d', unit.d);
        }
        this.addDescription(unit, obj);
        if (unit.data.f !== undefined) {
            this.addUnitStatusIcons(obj, unit.data.f);
        }
        if (unit.morfIcon !== undefined && unit.morfIcon) {
            this.morfUnitIcon(obj, unit.morfIcon, unit.icon);
        }

        return obj;
    };

    this.unitToUnitField = function(unit, x, y) {
        unit.data('x', x);
        unit.data('y', y);
        $('.battle-border .unit-field').append(unit);
        unit.css({
            'margin-left': x * MageS.Game.cellSize + 'rem',
            'margin-top': y * MageS.Game.cellSize + 'rem'
        });
    };

    this.unitToCell = function(unit, target) {
        if (!target) {
            target = '.battle-field.current';
        }
        var x = unit.data('x');
        var y = unit.data('y');
        unit.css({
            'margin-left' : '0',
            'margin-top' : '0'
        });
        $(target + ' .cell.x-' + x + '.y-' + y).append(unit);
    };
    this.allToCell = function(mark) {
        $('.battle-border .unit-field .unit').each(function() {
            if (mark) {
                $(this).addClass(mark);
            }
            MageS.Game.units.unitToCell($(this));
        })
    };
    this.allBackToField = function() {
        $('.battle-field.current .unit').each(function() {
            var unit = $(this);
            if (unit.hasClass('toDelete')) {
                unit.remove();
            } else {
                MageS.Game.units.unitToUnitField(unit, unit.data('x'), unit.data('y'));
            }
        });
    };
    
    this.morfUnitIcon = function(obj, morf, iconName) {
        switch (iconName) {
            case 'icon-spider-2':
                switch(morf) {
                    case 'baby':
                        obj.find('svg .body').css('fill', '#aaa');
                        obj.find('svg .head').css('fill', '#bab');
                        break;
                }
                break;
        }   
    };
    this.addDescription = function(unit, unitObject) {
        var temaplate = $('#template-unit-tooltip').html();
        Mustache.parse(temaplate);

        var rendered = Mustache.render(temaplate, {'id': unit.id, 'name': unit.name,
            'description' : unit.description, 'health': unit.data.health, 'maxHealth': unit.maxHealth
        });
        var obj = $(rendered);
        $('.tooltip-unit-area').append(obj);
        if (this.game.device == 'pc') {
            unitObject.on({
                'mouseenter': function () {
                    var id = $(this).data('id');
                    $('.tooltip-unit-area .unit-tooltip.id-' + id).addClass('hover');
                },
                'mouseleave': function () {
                    var id = $(this).data('id');
                    $('.tooltip-unit-area .unit-tooltip.id-' + id).removeClass('hover');
                }
            });
        } else {
            unitObject.on({

                'click': function () {
                    MageS.Game.units.mobileUnitClick($(this));
                }
            });
        }
    };
    
    this.moveUnit = function(data, stage) {
        var unit = $('.battle-border .unit-field .unit.id-' + data.id);
        if (unit.length < 1) {
            info('unit with ID = ' + data.id + ' was not on the map');
            // ok we don't have that unit at all.
            var unit = this.drawUnit(data.data, data.oldX, data.oldY);
        }

        // var cellToGo = $('.battle-field.current .cell.x-' + data.x + '.y-' + data.y);
        // $('.unit-field').append(unit);
        // unit.css({
        //     'margin-left': oldX * this.game.cellSize + 'rem',
        //     'margin-top': oldY * this.game.cellSize + 'rem'
        // });
        unit.animate({
            'margin-left' : parseInt(data.x) * this.game.cellSize + 'rem',
            'margin-top' : parseInt(data.y) * this.game.cellSize + 'rem'
        }, {
            duration : MageS.Game.animations.animationTime,
            'complete': (function () {
                    $(this).data('x', data.x)
                        .data('y', data.y);
                    if (Math.abs(data.x) > MageS.Game.fieldRadius || Math.abs(data.y) > MageS.Game.fieldRadius) {
                        $(this).remove();
                    }
                    // if (cellToGo.length < 1) {
                    //     MageS.Game.animations.singleAnimationFinished(stage);
                    //     $(this).remove();
                    //     return;
                    // }
                    // $(this).css({
                    //     'margin-left' : '0',
                    //     'margin-top' : '0'
                    // });
                    // cellToGo.append($(this));

                    MageS.Game.animations.singleAnimationFinished(stage);
                }
            )});
        MageS.Game.units.animateMove(unit);
    };

    this.mobileUnitClick = function(el) {
        MageS.Game.inventory.showInventory();
        var id = el.data('id');
        var tooltip = $('.tooltip-unit-area .unit-tooltip.id-' + id);
        if (tooltip.hasClass('hover')) {
            MageS.Game.spellcraft.hideShadow();
        } else {
            MageS.Game.spellcraft.showShadow(function() { MageS.Game.units.mobileUnitClick(el); });
        }
        tooltip.toggleClass('hover');
    };

    this.animateMove = function(unit) {
        var iconName = unit.data('icon');
        switch (iconName) {
            case 'icon-spider-2':
                this.spiderAnimateMove(unit);
                break;
        }
    };

    this.meleeAttack = function(data, container, attackId, stage) {
        var unit = $('.battle-border .unit.id-' + data.attackerId);
        var iconName = unit.data('icon');

        switch (iconName) {
            case 'icon-spider-2':
                this.spiderAnimateMelee(unit, data, container);
                break;
            case 'icon-mouse-1':
                this.mouseAnimateMelee(unit, data, container);
                break;
        }

        setTimeout(function(){
            MageS.Game.attacks.finishAttack(attackId, stage);
        }, 1000);
    };

    this.rotateUnitToTarget = function (unit, data) {
        var calculations = this.game.spells.getDistanceBetweenTwoDots(data.fromX, data.fromY, data.targetX, data.targetY);
        var angleTo = calculations[1] + 90;
        var currentAngle = unit.data('d') * 90;
        if (angleTo - currentAngle > 180) {
            angleTo -= 360;
        } else if (currentAngle - angleTo > 180) {
            angleTo += 360;
        }
        unit.find('.rotate-div').animateRotate(currentAngle, angleTo , 150);
        unit.data('attackAngle' , angleTo);
    };
    this.rotateUnitBack = function (unit) {
        var currentD = unit.data('d');
        var angleFrom = unit.data('attackAngle');
        unit.find('.rotate-div').animateRotate(angleFrom, currentD * 90, 150);
        unit.data('attackAngle', '');
    };

    this.moveBodyPartForAttack = function(el, moveDistance, scale, options) {
        var timeoutIn = 150;
        var timeoutOut = 650;
        var durationIn = 500;
        var durationOut = 200;
        setTimeout(function(){
            el.animate({ whyNotToUseANonExistingProperty: 100 }, {
                step: function(now,fx) {
                    var k = now / 100;
                    $(this)[0].style.transform = 'translateY(' + (k * moveDistance) + 'px) scale(' + (1 + (k * scale)) + ')'
                },
                duration:durationIn, easing:'easeOutBounce', queue:false
            });
        }, timeoutIn);
        setTimeout(function(){
        el.animate({ whyNotToUseANonExistingProperty: 100 }, {
            step: function(now,fx) {
                var k = now / 100;
                $(this)[0].style.transform = 'translateY(' + (moveDistance - (k * moveDistance)) + 'px) scale(' + (1 + scale - (k * scale)) + ')'
            },
            duration:durationOut, easing:'linear', queue:false
        });
        }, timeoutOut);
    };

    this.spiderAnimateMove = function(unit) {
        var group1 = unit.find('.group-leg-1');
        group1.each(function(){
            MageS.Game.monimations.swing($(this), 20, 350);
        })
    };
    
    this.spiderAnimateMelee = function(unit, data, container) {
        this.rotateUnitToTarget(unit, data);
        var heads = unit.find('.head,.eyes');
        var body = unit.find('.body');
        var leg = unit.find('.leg');
        var slash = this.game.spells.createIcon('icon-claw-slashes', 'color-white');
        container.append(slash);
        slash[0].style.transform = 'rotate(' + (unit.data('attackAngle') - 90) + 'deg)';
        slash.css({opacity:0});
        this.moveBodyPartForAttack(heads, -100, 0.25, {});
        this.moveBodyPartForAttack(body, -50, 0.25, {});
        this.moveBodyPartForAttack(leg, -30, 0, {});
        setTimeout(function(){
            slash.animate({opacity:1}, {duration:200,easing:'easeInExpo', complete:function(){
                var that = $(this);
                setTimeout(function() {
                    that.remove();
                }, 100);
            }});
        }, 450);
        setTimeout(function(){
            MageS.Game.units.rotateUnitBack(unit);
        }, 850);
    };

    this.mouseAnimateMelee = function(unit, data, container) {
        this.rotateUnitToTarget(unit, data);
        var heads = unit.find('.head,.eyes');
        var body = unit.find('.body');
        var ears = unit.find('.ears');
        var slash = this.game.spells.createIcon('icon-claw-slashes', 'color-white');
        container.append(slash);
        slash[0].style.transform = 'rotate(' + (unit.data('attackAngle') - 90) + 'deg)';
        slash.css({opacity:0});
        this.moveBodyPartForAttack(heads, -100, 0.25, {});
        this.moveBodyPartForAttack(ears, -90, 0.25, {});
        this.moveBodyPartForAttack(body, -50, 0.25, {});
        setTimeout(function(){
            slash.animate({opacity:1}, {duration:200,easing:'easeInExpo', complete:function(){
                var that = $(this);
                setTimeout(function() {
                    that.remove();
                }, 100);
            }});
        }, 450);

        setTimeout(function(){
            MageS.Game.units.rotateUnitBack(unit);
        }, 850);
    };

    this.unitSpawn = function(data, stage) {
        var icon = 'icon-bullet-around-side-line';
        var options = {
            'moveLeft': ((0.5 + parseInt(data.targetX)) * MageS.Game.cellSize) + 'rem',
            'moveTop': ((0.5 + parseInt(data.targetY)) * MageS.Game.cellSize) + 'rem',
            'time': 0.8,
            'beamWidth': 12,
            'segment1': ["100%", "100%"],
            'segment2': ["-40%", "0%"],
            'delete': true
        };
        for (var i = 0; i < 5; i++) {
            MageS.Game.spells.beamStrike(3, 360 / 5 * i, icon, MageS.Game.color.purpleDark, options);
        }
        setTimeout(function(){
            MageS.Game.animations.singleAnimationFinished(stage);
        }, 800);
    };

    this.animateDeath = function(unit, stage) {
        var iconName = unit.data('icon');

        switch (iconName) {
            case 'icon-fireImp-1':
                this.standartDeath(unit);
                var options = {'marginLeft' : unit.data('x'), 'marginTop' : unit.data('y')};
                MageS.Game.spells.fire.blastSunRing('color-red-bright', options);
                setTimeout(function() {
                    MageS.Game.spells.fire.blastSunRing('color-yellow', options);
                }, 100);
                setTimeout(function() {
                    MageS.Game.spells.fire.blastSunRing('color-white', options);
                }, 200);
                break;
            default:
                this.standartDeath(unit);
        }

        setTimeout(function(){
            MageS.Game.animations.singleAnimationFinished(stage);
        }, 500);
    };
    
    this.standartDeath = function(unit) {
        $('.tooltip-unit-area .id-' + unit.data('id')).remove();
        unit.animate({
            'opacity': 0
        }, {
            duration: 1000,
            'complete': (function () {
                    $(this).remove();
                }
            )
        });
    };

    this.addUnitStatusIcons = function(unit, flags) {
        this.addFlag(unit, flags);
    };

    this.addFlag = function(el, flags) {
        var iconName = '';
        var color = '';
        var addClass = '';
        for(var flag in flags) {
            switch (flag) {
                case 'frozen':
                    iconName = 'icon-cracked-glass';
                    color = '#37A4F9';
                    break;
                case 'burn':
                    iconName = 'icon-flame-tunnel';
                    color = '#FF8360';
                    addClass = 'under';
                    break;
                case 'web':
                    iconName = 'icon-spider-web';
                    color = '#FFF';
                    break;
                case 'quicksand':
                    iconName = 'icon-sand';
                    color = MageS.Game.color.sand;
                    break;
                case 'stoned':
                    iconName = 'icon-stoned';
                    color = MageS.Game.color.grey;
                    break;
                default :
                    info('Flag "' + flag + '" is not implemented');
                    continue;
                    break;
            }

            var div = $('<div class="fuckthisshit svg flag-' + flag + ' ' + addClass + ' unit-status"><svg viewBox="0 0 512 512"></svg></div>');
            var icon = $(this.game.svg).find('#' + iconName + ' path').css({'fill': color});
            div.find('svg').append(icon.clone());
            el.prepend(div);
        }
    }

};

