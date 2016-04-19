/**
 * Created by Ilya Rubinchik (ilfate) on 10/04/16.
 */

MageS.Home = function (game) {
    this.game = game;
    this.mageType = '';

    this.init = function() {
        $('a#mage-create-button').on('click', function () {
            MageS.Game.home.showCreateMagePopUp();
        });
        $('.player-mage-list .mage-type-select').on('click', function() {
            MageS.Game.home.mageType = $(this).data('type');
            $('.player-mage-list .single-mage-type').css('height', '2.5rem');
            var el = $(this).parent();
            el.append($('.player-mage-list .last-step').show());
            el.animate({
                height: '6.5rem'
            }, {easing:'easeOutBounce'})
        });
        $('#create-mage-pop-up a.submit').on('click', function() {
            var name = $(this).prev().prev().val();
            var type = MageS.Game.home.mageType;
            if (!name || !type) {
                //display error
                return false;
            }
            Ajax.json('/Spellcraft/createMage', {
                data: 'name=' + name + '&type=' + type,
                callBack : function(data){ MageS.Game.callback(data) }
            });
        });
    };

    this.showCreateMagePopUp = function() {
        $('#create-mage-pop-up').slideDown({easing:'easeOutBounce'});
    };

    this.startAnimation = function() {
        info('awdawd');
        var containerEl = $('.animation-layer');
        var base = 0;
        for (var i = 0; i < 10; i++) {
            var svgEl = $('<div></div>').width('1').height('1').css({'position': 'absolute'});
            svgEl.svg({
                onLoad: function (svg) {
                    svg.circle(2 * MageS.Game.rem, 0, 0.25 * MageS.Game.rem,
                        {fill: 'none', stroke: 'none'});
                }
            });
            MageS.Game.monimations.portalStar(svgEl, 2 * MageS.Game.rem, 0.25 * MageS.Game.rem, base, Math.random() * 1500);
            base += 36;
            containerEl.append(svgEl);
        }

    }

};

