/**
 * Created by Ilya Rubinchik (ilfate) on 10/04/16.
 */

MageS.Chat = function (game) {
    this.game = game;
    this.baseHeight = 0;
    this.inventorySize = 0;

    this.buildChat = function() {

        if (this.game.device == 'mobile') {
            var hammertime = new Hammer(document.getElementById('last-message'), {});
            hammertime.get('pan').set({direction: Hammer.DIRECTION_ALL});
            hammertime.get('swipe').set({direction: Hammer.DIRECTION_VERTICAL});

            hammertime.on('panstart', function (ev) { MageS.Game.chat.swipeStart(ev); });
            hammertime.on('panup', function (ev) { MageS.Game.chat.swipeUp(ev); });
            hammertime.on('pandown', function (ev) { MageS.Game.chat.swipeDown(ev); });
            hammertime.on('panend', function (ev) { MageS.Game.chat.swipeEnd(ev); });
        }
        $( window ).resize(function() { MageS.Game.chat.windowResize()});
        $( window ).bind('orientationchange', function() { MageS.Game.chat.windowResize()});
        this.windowResize();
    };

    this.windowResize = function() {
        MageS.Game.pageResize();
        var chat = $('.bottom-panel');
        var lastMessage = $('.bottom-panel .last-message');
        var chatSize = 0;
        if (this.game.device == 'mobile') {
            var rightPanel = $('.right-panel');
            var rightPanelOffset = rightPanel.offset().top;
            rightPanel.hide();
            var inventorySize = (($(document).height() - rightPanelOffset) / this.game.rem) - this.game.cellSize;
            rightPanel.show();

            $('.inventory, .spellBook, .right-panel').css({'height':inventorySize + 'rem'});
            chatSize = inventorySize;
            this.baseHeight = this.game.cellSize;
            this.inventorySize = inventorySize;
        } else {
            var distanceToBottom = $(document).height() - chat.offset().top - chat.height();
            if (distanceToBottom > 10) {
                this.baseHeight = (chat.height() + distanceToBottom - 2 ) / this.game.rem;
            }
            chatSize = 5 * this.game.cellSize;
        }
        lastMessage.height(this.baseHeight + 'rem');
        $('.chat-container').height(chatSize + 'rem');
    };

    this.hideChat = function() {
        if (this.game.device == 'mobile') {
            var bottomEl = $('.bottom-panel');
            if (bottomEl.hasClass('mobile-open')) {
                $('.bottom-panel .chat-container').hide();
                bottomEl.animate({
                    'margin-top':'0rem',
                    'height': this.baseHeight + 'rem'
                }, {'easing': 'easeOutElastic'});
                bottomEl.removeClass('mobile-open');
                $('.bottom-panel .last-message .cover .svg-icon').css({'transform': 'rotate(180deg)'});
                $('.bottom-panel .last-message .cover .middle').css({ 'opacity':0 });
            }
        }
    };
    this.showChat = function() {
        var bottomEl = $('.bottom-panel');
        if (!bottomEl.hasClass('mobile-open') && parseInt(bottomEl.height()) > this.baseHeight + this.game.rem) {
            MageS.Game.hideMenu();
            bottomEl.addClass('mobile-open');
            var size = this.inventorySize;
            bottomEl.animate({
                'margin-top': '-' + size + 'rem',
                'height': this.baseHeight + size + 'rem'
            }, {'easing': 'easeOutElastic'});
            bottomEl.find('.chat-container').css({opacity: 1, 'overflow-y':'scroll'});
            $('.bottom-panel .last-message .cover .svg-icon').css({'transform': 'rotate(0deg)'});
            $('.bottom-panel .last-message .cover .middle').css({'opacity': 1});
        }
    };

    this.swipeStart = function(event) {
        if (!$('.bottom-panel').hasClass('mobile-open')) {
            $('.bottom-panel .chat-container').show().css({'opacity': '0'});
        }
    };
    this.swipeEnd = function(event) {
        var bottomEl = $('.bottom-panel');
        if (bottomEl.hasClass('mobile-open')) {
            this.hideChat();
        } else {
            this.showChat();
        }
    };

    this.swipeUp = function(event) {
        if ($('.bottom-panel').hasClass('mobile-open')) {
            return;
        }
        $('.bottom-panel').css({
            'margin-top':'-' + (event.distance / this.game.rem) + 'rem',
            'height': this.baseHeight + (event.distance / this.game.rem) + 'rem'
        });
        $('.bottom-panel .chat-container').css({'opacity':event.distance / 100});
        $('.bottom-panel .last-message .cover .middle').css({
            'opacity':event.distance / 50
        });
        var angle = 180 - event.distance * 2;
        if (angle >= 0) {
            $('.bottom-panel .last-message .cover .svg-icon').css({'transform': 'rotate(' + angle + 'deg)'});
        }
    };

    this.swipeDown = function(event) {
        if (!$('.bottom-panel').hasClass('mobile-open')) {
            return;
        }
        $('.bottom-panel').css({
            'margin-top':'-' + ((5 * this.game.cellSize) - (event.distance/this.game.rem)) + 'rem',
            'height': this.baseHeight + (5 * this.game.cellSize) - (event.distance / this.game.rem) + 'rem'
        });
        $('.bottom-panel .chat-container').css({'opacity': 1 - event.distance / 100});
        $('.bottom-panel .last-message .cover .middle').css({
            'opacity':1 - event.distance / 50
        });
        var angle = event.distance * 1.5;
        if (angle <= 180) {
            $('.bottom-panel .last-message .cover .svg-icon').css({'transform': 'rotate(' + angle + 'deg)'});
        }
    };

    this.postMessages = function(messages) {
        for (var i in messages) {
            var message = messages[i];
            this.postMessage(message.message, message.type, message.data);
        }
    };
    this.postMessage = function (message, type, data) {
        if (type === undefined) {
            type = 'chat';
        }
        if (type == 'chat') {
            var temaplate = $('#template-chat-message').html();
            Mustache.parse(temaplate);
            var rendered = Mustache.render(temaplate, {'content': message, 'type': type});
            var obj = $(rendered);
            obj.find('.text').css({'color':'#F07818'}).animate({'color':'#5E412F'});
            $('.bottom-panel .chat-container').append(obj.clone());
            $('.bottom-panel .last-message .content').html('').append(obj);

            var height = 0;
            $('.chat-container .chat-message').each(function(i, value){
                height += parseInt($(this).height());
            });

            height += '';
            $('.chat-container').animate({scrollTop: height});

        } else if (type == 'dialog') {
            if (data.x && data.y) {

            }
        }
    };

    this.dialogMessage = function(data, stage) {
        var delay = 0;
        if (data.delay !== undefined) {
            delay = data.delay;
        }
        setTimeout(function(){
            var directions = ['top', 'right', 'bottom', 'left'];
            var cell = $('.battle-border .pattern-cell.x-' + data.targetX + '.y-' + data.targetY);
            // cell.data('content', data.message);
            var direction = directions[2];
            if (data.direction !== undefined) {
                direction = directions[data.direction];
            } else if (data.targetX == data.targetY && data.targetY == 0) {
                direction = array_rand([directions[0], directions[2]]);
            } else {
                if (data.targetY > 3) { direction = directions[0]; }
                else if (data.targetY < -3) { direction = directions[2]; }
                else if (data.targetX < -3) { direction = directions[1]; }
                else if (data.targetX > 3) { direction = directions[3]; }
                else { direction = array_rand([directions[0], directions[2]]); }
            }
            var options =  {content:data.message, placement:direction, trigger:'manual'};// auto
            cell.popover(options);
            cell.popover('show');
            setTimeout(function() {
                    cell.popover('hide');
                    cell.popover('destroy');
                    if (data.noPost === undefined) {
                        MageS.Game.chat.postMessage(data.message, 'chat')
                    }
                }, data.time + 150);
            if (stage) {
                setTimeout(function () {
                    MageS.Game.animations.singleAnimationFinished(stage);
                }, data.showTime + 150)
            }
        }, delay);
    }


};

