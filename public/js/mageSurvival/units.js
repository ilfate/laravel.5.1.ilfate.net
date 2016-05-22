/**
 * Created by Ilya Rubinchik (ilfate) on 08/04/16.
 */




MageS.Units = function (game) {
    this.game = game;

    this.drawUnit = function(unit, x, y, target) {
        if (!target) {
            target = '.battle-field.current';
        }
        var temaplate = $('#template-unit').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'id': unit.id, 'type': unit.type});
        var obj = $(rendered);
        var icon = $(this.game.svg).find('#' + unit.icon + ' path');
        obj.data('icon', unit.icon);
        obj.find('svg').append(icon.clone());
        if (unit.iconColor !== undefined) { obj.find('.svg').addClass(unit.iconColor); }
        $(target + ' .cell.x-' + x + '.y-' + y).append(obj);
        if (unit.d !== undefined) {
            if ( unit.d > 0) {
                obj.find('.rotate-div')[0].style.transform = 'rotate(' + (unit.d * 90) + 'deg)';
            }
            obj.data('d', unit.d);
        }
        if (unit.data.f !== undefined) {
            this.addUnitStatusIcons(obj, unit.data.f);
        }

        return obj;
    };

    this.animateMove = function(unit) {
        var iconName = unit.data('icon');
        switch (iconName) {
            case 'icon-spider-2':
                this.spiderAnimateMove(unit);
                break;
        }
    };

    this.meleeAttack = function(data, container, attackId) {
        var unit = $('.battle-border .cell.x-' + data.fromX + '.y-' + data.fromY + ' .unit');
        var iconName = unit.data('icon');

        switch (iconName) {
            case 'icon-spider-2':
                this.spiderAnimateMelee(unit, data, container);
                break;
        }

        setTimeout(function(){
            MageS.Game.attacks.finishAttack(attackId);
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
        setTimeout(function(){
            heads.animate({ whyNotToUseANonExistingProperty: 100 }, {
                step: function(now,fx) {
                    $(this)[0].style.transform = 'translateY(' + (-now) + 'px) scale(' + (1 + (now / 400)) + ')'
                },
                duration:500, easing:'easeOutBounce', queue:false
            });
            body.animate({ whyNotToUseANonExistingProperty: 100 }, {
                step: function(now,fx) {
                    $(this)[0].style.transform = 'translateY(' + (-now/2) + 'px) scale(' + (1 + (now / 400)) + ')'
                },
                duration:500, easing:'easeOutBounce', queue:false
            });
            leg.animate({ whyNotToUseANonExistingProperty: 100 }, {
                step: function(now,fx) {
                    $(this)[0].style.transform = 'translateY(' + (-now/4) + 'px)'
                },
                duration:500, easing:'easeOutBounce', queue:false
            });
        }, 150);
        setTimeout(function(){
            slash.animate({opacity:1}, {duration:200,easing:'easeInExpo', complete:function(){
                var that = $(this);
                setTimeout(function() {
                    that.remove();
                }, 100);
            }});
        }, 450);
        setTimeout(function(){
            heads.animate({ whyNotToUseANonExistingProperty: 100 }, {
                step: function(now,fx) {
                    $(this)[0].style.transform = 'translateY(' + (-100 + (now)) + 'px) scale(' + (1.25 - (now / 400)) + ')'
                },
                duration:200, easing:'linear', queue:false
            });
            body.animate({ whyNotToUseANonExistingProperty: 100 }, {
                step: function(now,fx) {
                    $(this)[0].style.transform = 'translateY(' + (-50 + (now/2)) + 'px) scale(' + (1.25 - (now / 400)) + ')'
                },
                duration:200, easing:'linear', queue:false
            });
            leg.animate({ whyNotToUseANonExistingProperty: 100 }, {
                step: function(now,fx) {
                    $(this)[0].style.transform = 'translateY(' + (-25 + (now/4)) + 'px)'
                },
                duration:200, easing:'linear', queue:false
            });
        }, 650);
        setTimeout(function(){
            MageS.Game.units.rotateUnitBack(unit);
        }, 850);
    };

    this.animateDeath = function(unit) {
        var iconName = unit.data('icon');

        switch (iconName) {
            case 'icon-fireImp-1':
                this.standartDeath(unit);
                var cell = unit.parents('.cell')
                var options = {'marginLeft' : cell.data('x'), 'marginTop' : cell.data('y')};
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
            MageS.Game.animations.singleAnimationFinished();
        }, 500);
    };
    
    this.standartDeath = function(unit) {
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
                default :
                    info('Flag "' + flag + '" is not implemented');
                    return;
                    break;
            }

            var div = $('<div class="svg flag-' + flag + ' ' + addClass + ' unit-status"><svg viewBox="0 0 512 512"></svg></div>');
            var icon = $(this.game.svg).find('#' + iconName + ' path').css({'fill': color});
            div.find('svg').append(icon.clone());
            el.prepend(div);
        }
    }

};

