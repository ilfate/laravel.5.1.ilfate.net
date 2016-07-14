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
        if (object.config.morfIcon !== undefined && object.config.morfIcon) {
            this.morfObjectIcon(obj, object.config.morfIcon, object.config.icon);
        }

        this.appendAnimations(object.config.icon, obj);
        return obj;
    };
    this.morfObjectIcon = function(obj, morf, iconName) {
        switch (iconName) {
            case 'icon-water-huracane':
                switch(morf) {
                    case 'lightBlue':
                        obj.find('svg .blue-wave').css('fill', MageS.Game.color.lightBlue);
                        // obj.find('svg .head').css('fill', '#bab');
                        break;
                }
                break;
        }
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
            case 'wallExplode':
                var icon = 'icon-wall-particle-';
                var options = {
                    time:400,
                    startRandomRange:MageS.Game.cellSize * MageS.Game.rem,
                    randomRange:4 * MageS.Game.cellSize * MageS.Game.rem,
                    scale:0.25, rotate:true};
                for (var i = 0; i < 15; i ++) {
                    MageS.Game.spells.moveIcon(icon + ((i % 4) + 1), 'color-grey', data.targetX, data.targetY, data.targetX, data.targetY, options);
                }
                MageS.Game.monimations.camShake('X', 300, 6, {});
                setTimeout(function() {MageS.Game.animations.singleAnimationFinished(stage);}, 400);
                break;
            case 'fountainHeal':
                var options = {beamWidth:20, 'delete':true};

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
            case 'lightingZap':
                //var screenOptions = {color:'#000', 'delete':true, 'duration': 200, deleteDelay:300, deleteDuration:200};
                //this.spells.addScreen(screenOptions);
                var icons = ['icon-bullet-lightning', 'icon-bullet-lightning-2'];
                var options = {
                    // 'moveLeft': ((0.5 + data.targetX) * MageS.Game.cellSize) + 'rem',
                    // 'moveTop': ((0.5 + data.targetY) * MageS.Game.cellSize) + 'rem',
                    'time': 0.1,
                    'beamWidth': 10,
                    'segment1': ["100%", "100%"],
                    'segment2': ["0%", "100%"],
                    'delete':true,
                    'delay': 100,
                    'yesIWantToHaveBlinkBug': true,
                };
                // MageS.Game.monimations.camShake('Y', 200, 8, 300, false, {el:$('body')});
                var icon = '';
                setTimeout(function() {
                    for (var i = 0; i < data.targets.length; i++) {
                        icon = array_rand(icons);
                        MageS.Game.spells.beam(data.centerX, data.centerY, data.targets[i][0], data.targets[i][1], '#FFF', icon, options);
                    }
                }, 300);
                setTimeout(function() {MageS.Game.animations.singleAnimationFinished(stage);}, 500);
                break;
        }
    };

    this.move = function(data, stage) {

        var object = $('.battle-field.current .object.id-' + data.id);
        if (object.length < 1) {
            info('object with ID = ' + data.id + ' was not on the map');
            
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

