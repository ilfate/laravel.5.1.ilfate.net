/**
 * Created by Ilya Rubinchik (ilfate) on 10/04/16.
 */

MageS.Chat = function (game) {
    this.game = game;
    this.baseHeight = 0;

    this.buildChat = function() {
        var chat = $('.bottom-panel');
        var lastMessage = $('.bottom-panel .last-message');
        var distanceToBottom = $(document).height() - chat.offset().top - chat.height();

        if (distanceToBottom > 10) {
            this.baseHeight = (chat.height() + distanceToBottom - 2 ) / this.game.rem;
            //chat.height(this.baseHeight + 'rem');
            lastMessage.height(this.baseHeight + 'rem');

            $('.chat-container').height((5 * this.game.cellSize) + 'rem');
        }

        if (this.game.device == 'mobile') {
            var hammertime = new Hammer(document.getElementById('last-message'), {});
            hammertime.get('pan').set({ direction: Hammer.DIRECTION_ALL });
            hammertime.get('swipe').set({ direction: Hammer.DIRECTION_VERTICAL });

            hammertime.on('panstart',    function(ev) { MageS.Game.chat.swipeStart(ev); });
            hammertime.on('panup',    function(ev) { MageS.Game.chat.swipeUp(ev); });
            hammertime.on('pandown',  function(ev) { MageS.Game.chat.swipeDown(ev); });
            hammertime.on('panend',  function(ev) { MageS.Game.chat.swipeEnd(ev); });
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
            $('.bottom-panel .chat-container').hide();
            bottomEl.animate({
                'margin-top':'0rem',
                'height': this.baseHeight + 'rem'
            }, {'easing': 'easeOutElastic'});
            bottomEl.removeClass('mobile-open');
            $('.bottom-panel .last-message .cover .svg-icon').css({'transform': 'rotate(180deg)'});
            $('.bottom-panel .last-message .cover .middle').css({ 'opacity':0 });
        } else {
            if (parseInt(bottomEl.height()) > this.baseHeight + this.game.rem) {
                bottomEl.addClass('mobile-open');
                bottomEl.animate({
                    'margin-top':'-' + (5 * this.game.cellSize) + 'rem',
                    'height': this.baseHeight + (5 * this.game.cellSize) + 'rem'
                }, {'easing': 'easeOutElastic'});
                bottomEl.find('.chat-container').css({opacity:1});
                $('.bottom-panel .last-message .cover .svg-icon').css({'transform': 'rotate(0deg)'});
                $('.bottom-panel .last-message .cover .middle').css({ 'opacity':1 });
            }
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
        if (180 - event.distance * 2 > 0) {
            $('.bottom-panel .last-message .cover .svg-icon').css({'transform': 'rotate(' + (180 - event.distance * 2) + 'deg)'});
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
        if (event.distance * 2 < 180) {
            $('.bottom-panel .last-message .cover .svg-icon').css({'transform': 'rotate(' + (event.distance * 2) + 'deg)'});
        }
    };

    this.postMessages = function(messages) {
        for (var i in messages) {
            var message = messages[i];
            this.postMessage(message.message, message.type, message.data);
        }
    };
    this.postMessage = function (message, type, data) {
        info(message);
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
            info('DIALOG MESSAGE =' + message);
            if (data.x && data.y) {

            }
        }
    };


};

