/**
 * Created by Ilya Rubinchik (ilfate) on 10/04/16.
 */

MageS.Home = function (game) {
    this.game = game;
    this.mageType = '';

    this.createMageSubmitFormAction = function(){
        var name = $('#create-mage-pop-up .last-step .mage-name').val();
        var type = MageS.Game.home.mageType;
        info(name);
        if (!name || !type) {
            //display error
            return false;
        }
        Ajax.json('/Spellcraft/createMage', {
            data: 'name=' + name + '&type=' + type + '&device=' + MageS.Game.device,
            callBack : function(data){ MageS.Game.callback(data) }
        });
    };

    this.init = function() {
        $('a#mage-create-button').on('click', function () {
            MageS.Game.home.showCreateMagePopUp();
        });
        $('.player-mage-list .mage-type-select').on('click', function() {
            MageS.Game.home.mageType = $(this).data('type');
            var el = $(this).parent();
            el.append($('.player-mage-list .last-step').slideDown());
        });
        $('#create-mage-pop-up a.submit').on('click', function() {
            MageS.Game.home.createMageSubmitFormAction();
        });
        $('#create-mage-pop-up form').on('submit', function() {
            MageS.Game.home.createMageSubmitFormAction();
            return false;
        });
        $('.open-dead-info').on('click', function() {
            $(this).parents('.dead-mage').next().slideToggle();
            if ($(this).hasClass('active')) {
                $(this).find('i').animateRotate(180, 0, 200, 'easeInOutQuad');
                $(this).removeClass('active');
            } else {
                // info($(this).find('svg'));
                $(this).find('i').animateRotate(0, 180, 400, 'easeInOutQuad');
                $(this).addClass('active');
            }
        })
        $('.single-mage-type.locked').on('click', function() {
            $(this).find('.requirements').slideToggle();
        });
        $('.progress-bar.colored').each(function(){
            var color = $(this).data('color');
            $(this).css({'background-color': MageS.Game.color[color]});
        });
    };

    this.showCreateMagePopUp = function() {
        $('#create-mage-pop-up').slideDown({easing:'easeOutBounce'});
    };

    this.startAnimation = function() {
        var containerEl = $('.animation-layer');
        var size = containerEl.width() / 10 * 6;
        var base = 0;
        for (var i = 0; i < 10; i++) {
            var svgEl = $('<div></div>').width('1').height('1').css({'position': 'absolute'});
            svgEl.svg({
                onLoad: function (svg) {
                    svg.circle(0, size, 0.25 * MageS.Game.rem,
                        {fill: 'none', stroke: 'none'});
                }
            });
            MageS.Game.monimations.portalStar(svgEl, size, 0.25 * MageS.Game.rem, base, Math.random() * 1500);
            base += 36;
            containerEl.append(svgEl);
        }

    }

};

