/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */


function MageS () {

}
MageS = new MageS();

$(document).ready(function() {
    if ($('body.mage-survival').length) {
        MageS.Game = new MageS.Game();
        MageS.Game.init();
    }
});


MageS.Game = function () {
    this.color = {
        'blue': '#428BCA',
        'green': '#069E2D',
        'yellow': '#FFD416',
        'red': '#F21616',
        'orange': '#EF8354',
        'black': '#584D3D',
        'white': '#FFFFFF'
    };
    this.gameStatus = $('#game-status').val();
    this.rawData = [];
    this.worldType = 0;

    this.init = function () {
        switch (this.gameStatus) {
            case 'mage-list':
                $('a#mage-create-button').on('click', function () {
                    MageS.Game.showCreateMagePopUp();
                });
                $('#create-mage-pop-up a.mage-type').on('click', function() {
                    var parent = $(this).parent();
                    parent.find('.mage-name').show();
                    parent.find('a.submit').show();
                });
                $('#create-mage-pop-up a.submit').on('click', function() {
                    var name = $(this).prev().prev().val();
                    var type = $(this).prev().val();
                    if (!name || !type) {
                        //display error
                        return false;
                    }
                    Ajax.json('/MageSurvival/createMage', {
                        data: 'name=' + name + '&type=' + type,
                        callBack : function(data){ MageS.Game.callback(data) }
                    });
                });
                break;
            case 'battle':
                this.buildMap();
                $('#forward-button').on('click', function() {
                    Ajax.json('/MageSurvival/action', {
                        data: 'action=move&data=[]',
                        callBack : function(data){ MageS.Game.callback(data) }
                    });
                });
                break;
        }
        //$('.hexagon-click-area').on('click', function() {
        //    Hex.Game.action($(this).parent());
        //});
        //$('.hexagon-click-area').on('mouseenter', function() {
        //    Hex.Game.hover($(this), true);
        //    //$(this).parent().find('.hexagon').addClass('hover');
        //});
        //$('.hexagon-click-area').on('mouseleave', function() {
        //    Hex.Game.hover($(this), false);
        //    //$(this).parent().find('.hexagon.hover').removeClass('hover');
        //});
    }

    this.buildMap = function() {
        this.rawData = mageSurvivalData;
        this.worldType = this.rawData.world;
        for(var y in this.rawData.map) {
            for(var x in this.rawData.map[y]) {
                this.drawCell(this.rawData.map[y][x], x, y);
            }
        }
        this.drawMage(this.rawData.mage);
    }

    this.drawCell = function(cell, x, y) {
        var temaplate = $('#template-map-cell').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'x': x, 'y': y, 'class': cell});
        var obj = $(rendered);
        $('.battle-field').append(obj);
        obj.animate({
            'margin-left' : (x * 40) + 'px',
            'margin-top' : (y * 40) + 'px',
        })
    }

    this.drawMage = function(mageConf) {
        var temaplate = $('#template-mage').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'d': mageConf.d});
        var obj = $(rendered);
        $('.battle-field').append(obj);

    }

    this.callback = function(data) {
        if (data.action) {
            switch (data.action) {
                case 'mage-create':
                    window.location = '/MageSurvival';
                    break;
                case 'move':
                    info(data);
                    break;
            }
        }
    }

    this.showCreateMagePopUp = function() {
        $('#create-mage-pop-up').show();
    }

};

