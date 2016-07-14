/**
 * Created by Ilya Rubinchik (ilfate) on 08/04/16.
 */




MageS.Mage = function (game) {
    this.game = game;
    this.bind = [];

    this.cantMoveTill = false;

    this.moveStarted = false;
    this.moveLoaded = false;
    this.moveFinished = false;
    this.moveStage = false;
    this.beforeMoveD = false;

    this.drawMage = function(mageConf) {
        var temaplate = $('#template-mage').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'d': mageConf.d});
        var obj = $(rendered);
        var icons = this.game.getIcon('icon-mage-1');
        obj.find('svg').append(icons.clone());
        // $(this.game.svg).find('#icon-mage-1 path').each(function() {
        //     obj.find('svg').append($(this).clone());
        // });
        obj.animateRotate(0, mageConf.d * 90, 10);

        $('.mage-container').prepend(obj);
        if (mageConf.flags) {
            this.addMageStatus(mageConf.flags);
        }
        if (mageConf.firstTutorial !== undefined) {
            this.tutorialFirstMessage()
        }
    };
    
    this.moveMage = function(data, stage) {
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
        if (data.force !== undefined || data.bigMove !== undefined) {
            var newX = data.mage.x;
            var newY = data.mage.y;
            var oldX = data.mage.was.x;
            var oldY = data.mage.was.y;
            var baseMargin = this.game.fieldRadius * this.game.cellSize;
            var animateTime = this.game.animationTime;
            var dX = Math.abs(newX - oldX);
            var dY = Math.abs(newY - oldY);
            var dSum = dX + dY;
            if (dSum > 1) { animateTime = animateTime * 2; }
            newBattleField.css({
                'margin-left': baseMargin + (newX - oldX) * this.game.cellSize + 'rem',
                'margin-top': baseMargin + (newY - oldY) * this.game.cellSize + 'rem'
            });
            newBattleField.animate({
                'margin-left': baseMargin + 'rem',
                'margin-top': baseMargin + 'rem'
            }, {'duration': animateTime});
            MageS.Game.mage.moveFinished = false;
            this.startMove(oldX - newX, oldY - newY);
        }
        // if (data.bigMove !== undefined) {
        //
        // }


        // newBattleField.css({
        //     'margin-left': baseMargin + (newX - oldX) * this.game.cellSize + 'rem',
        //     'margin-top': baseMargin + (newY - oldY) * this.game.cellSize + 'rem'
        // });
        // var animateTime = this.game.animationTime;
        // var dX = Math.abs(newX - oldX);
        // var dY = Math.abs(newY - oldY);
        // var dSum = dX + dY;
        // if (dSum > 1) { animateTime = animateTime * 2; }

        // this.rotateTorso(animateTime, 2);
        // newBattleField.animate({
        //     'margin-left': baseMargin + 'rem',
        //     'margin-top': baseMargin + 'rem'
        // }, {'duration': animateTime});
        // var that = this;
        // $('.battle-field.current').animate({
        //     'margin-left': baseMargin - (newX - oldX) * this.game.cellSize + 'rem',
        //     'margin-top': baseMargin - (newY - oldY) * this.game.cellSize + 'rem'
        // }, {duration: animateTime,
        //     complete:function(){
        //         $('.battle-field.current').remove();
        //         $('.battle-field.new').removeClass('new').addClass('current');
        //         MageS.Game.units.allBackToField();
        //         MageS.Game.animations.singleAnimationFinished(stage);
        //     }});
        this.moveStage = stage;
        MageS.Game.mage.moveLoaded = true;
        if (MageS.Game.mage.moveFinished) {
            MageS.Game.mage.finishMove();
        }

    };

    this.startMove = function(x, y) {
        this.moveStarted = true;
        var baseMargin = this.game.fieldRadius * this.game.cellSize;
        
        var animateTime = this.game.animationTime;
        var dX = Math.abs(x);
        var dY = Math.abs(y);
        var dSum = dX + dY;
        if (dSum > 1) { animateTime = animateTime * 2; }
        this.rotateTorso(animateTime, 2);
        if (!this.game.spells.spellAnimationRunning) {
            this.mageMoveHands(animateTime);
        }
        MageS.Game.units.allToCell('toDelete');
        $('.battle-field.current').animate({
            'margin-left': baseMargin + x * this.game.cellSize + 'rem',
            'margin-top': baseMargin + y * this.game.cellSize + 'rem'
        }, {duration: animateTime,
            complete:function(){
                MageS.Game.mage.moveFinished = true;
                if (MageS.Game.mage.moveLoaded) {
                    MageS.Game.mage.finishMove();
                }
                // $('.battle-field.current').remove();
                // $('.battle-field.new').removeClass('new').addClass('current');
                // MageS.Game.units.allBackToField();
                // MageS.Game.animations.singleAnimationFinished(stage);
            }});
    };

    this.finishMove = function() {
        $('.battle-field.current').remove();
        $('.battle-field.new').removeClass('new').addClass('current');
        MageS.Game.units.allBackToField();
        MageS.Game.animations.singleAnimationFinished(this.moveStage);

        this.moveLoaded = false;
        this.moveFinished = false;
        this.moveStage = false;
        this.moveStarted = false;
        this.beforeMoveD = false;
    };
    
    this.cancelMove = function() {
        if (this.beforeMoveD !== false) {
            var el = $('.battle-border .mage');
            this.game.animations.rotate(
                el,
                {d: this.beforeMoveD, wasD:el.data('d')},
                false
            );
        }
        var baseMargin = this.game.fieldRadius * this.game.cellSize;
        $('.battle-field.current').animate({
            'margin-left': baseMargin + 'rem',
            'margin-top': baseMargin + 'rem'
        }, 200);
        MageS.Game.chat.dialogMessage({'targetX':0, 'targetY':0, 'message':'Wtf I just did? I can`t move there!', time:500});
    };

    this.addMageStatus = function(flags) {
        this.game.units.addFlag($('.mage-container .mage'), flags);
        for(var flag in flags) {
            switch (flag) {
                case 'web':
                    this.cantMoveTill = flags[flag];
                    break;
            }
        }
    };

    this.onLoad = function() {
        if (this.bind['onload'] !== undefined) {
            this.bind['onload']();
        }
    };

    this.tutorialFirstMessage = function() {
        this.bind['onload'] = function() {
            var text = 'You can swipe me to move!';
            if (MageS.Game.device == 'pc') {
                text = 'Move me with W A S D ';
            }
            var options = {
                time: 2000,
                direction: 2,
                message: text,
                targetX: 0,
                targetY: 0,
                noPost: true,
                delay: 1500
            };
            MageS.Game.chat.dialogMessage(options, false);
        }
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

