/**
 * Created by Ilya Rubinchik (ilfate) on 08/04/16.
 */




MageS.Objects = function (game) {
    this.game = game;

    this.drawObject = function(object, x, y, target) {
        if (!target) {
            target = '.battle-field.current';
        }
        var temaplate = $('#template-object').html();
        Mustache.parse(temaplate);
        var addClass = '';
        if (object.viewData.class !== undefined) {
            addClass = object.viewData.class;
        }
        var rendered = Mustache.render(temaplate, {'id': object.id, 'type':object.type, 'addClass' : addClass});
        var obj = $(rendered);
        var icon = $(this.game.svg).find('#' + object.config.icon + ' path');
        obj.find('svg').append(icon.clone());
        if (object.config.iconColor !== undefined) {
            obj.find('.svg').addClass(object.config.iconColor);
        }
        $(target + ' .cell.x-' + x + '.y-' + y).append(obj);
        if (object.config.centered !== undefined) {
            obj.addClass('centered');
        }

        this.appendAnimations(object.config.icon, obj);
        return obj;
    };

    this.appendAnimations = function(icon, obj) {

        switch (icon) {
            case 'icon-water-huracane':
                //this.spinObject(config.id, '.rotateble');
                var el = obj.find('.rotateble')
                new mojs.Tween({
                    repeat:   999,
                    delay:    10,
                    duration: 1500,
                    onUpdate: function (progress) {
                        var normalProgression = progress * 360;
                        el[0].style.transform = 'rotate(' + (normalProgression) + 'deg)';
                        el[1].style.transform = 'rotate(' + (-normalProgression) + 'deg)';
                        el[2].style.transform = 'rotate(' + (normalProgression) + 'deg)';
                    }
                }).run();
                break;
        }
    };
    this.spinObject = function(id, selector) {
        info('spinning object');
        var object = $('.battle-border .object.id-' + id);
        if (object.length == 0) return;
        var duration = 1000;
        object.find(selector).each( function() {
            MageS.Game.monimations.rotate($(this), 0, 360, duration, false, false, function(){ })
        });
        setTimeout(function(){ MageS.Game.objects.spinObject(id, selector)}, duration);
    };

    this.activate = function (data, stage) {
        switch(data.action) {
            case 'doorOpen':
                var door = $('.object.id-' + data.object);
                door.addClass('openDoor');
                setTimeout(function() {MageS.Game.animations.singleAnimationFinished(stage);}, 500);
                break;
            case 'doorClose':
                var door = $('.object.id-' + data.object);
                door.removeClass('openDoor');
                setTimeout(function() {MageS.Game.animations.singleAnimationFinished(stage);}, 500);
                break;
            case 'bombTrigger':
                var options = {'marginLeft' : data.targetX, 'marginTop' : data.targetY};
                MageS.Game.spells.fire.blastSunRing('color-red-bright', options);
                setTimeout(function() {
                    MageS.Game.spells.fire.blastSunRing('color-yellow', options);
                }, 100);
                setTimeout(function() {
                    MageS.Game.spells.fire.blastSunRing('color-white', options);
                }, 200);
                setTimeout(function() {MageS.Game.animations.singleAnimationFinished(stage);}, 500);
                break;
            case 'fountainHeal':
                var options = {beamWidth:20};

                var colors = [ '#529BCA', '#37A4F9', '#ffffff' ];
                var color = '';
                var lines = [
                    'icon-bullet-line-small-curve-right', 'icon-bullet-line-small-curve-left', 'icon-bullet-line', 'icon-bullet-sinus'
                ];
                var line = '';
                for (var i = 0; i < 10; i ++) {
                    color = array_rand(colors);
                    line = array_rand(lines);
                    MageS.Game.spells.beam(
                        data.targetX + (Math.random() * 0.5) - 0.25,
                        data.targetY + (Math.random() * 0.5) - 0.25,
                        0,
                        0,
                        color,
                        line,
                        options);
                }
                setTimeout(function() {MageS.Game.animations.singleAnimationFinished(stage);}, 500);
                break;
        }
    }

    this.move = function(data, stage) {

        var object = $('.battle-field.current .object.id-' + data.id);
        if (object.length < 1) {
            info('object with ID = ' + data.id + ' was not on the map');
            var unit2 =  $('.unit.id-' + data.id);
            if (unit2.length) {
                info('Animation object is there... but not at right place...');
            }
            // ok we don't have that unit at all.
            object = this.game.objects.drawObject(data.data, data.oldX, data.oldY);
            var oldX = data.oldX;
            var oldY = data.oldY;
        } else {
            var oldCell = object.parent('.cell');
            var oldX = oldCell.data('x');
            var oldY = oldCell.data('y');
        }

        var cellToGo = $('.battle-field.current .cell.x-' + data.x + '.y-' + data.y);
        $('.unit-field').append(object);
        object.css({
            'margin-left': oldX * this.game.cellSize + 'rem',
            'margin-top': oldY * this.game.cellSize + 'rem'
        });

        object.animate({
            'margin-left' : data.x * this.game.cellSize + 'rem',
            'margin-top' : data.y * this.game.cellSize + 'rem'
        }, {
            queue:false,
            duration: MageS.Game.animationTime,
            complete: function () {
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
                },
            'easing': 'linear'
            });
        MageS.Game.objects.animateMove(object);

    };

    this.animateMove = function(object) {
        
    }

};

