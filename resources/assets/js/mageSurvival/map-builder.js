/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */

function Map () {

}
Map = new Map();

$(document).ready(function() {
    if ($('body.mage-map-builder').length) {
        Map.Game = new Map.Game();
        Map.Game.init();
    }
});


Map.Game = function () {
    this.width = $('body').width();
    this.height = $('body').height();
    this.cellSize = 32;
    this.yStart = 0;
    this.xStart = 0;
    this.perRow = 0;
    this.perCol = 0;
    this.fullMap = {};
    this.offsetX = 0;
    this.offsetY = 0;
    this.coordinatOffsetX = 0;
    this.coordinatOffsetY = 0;

    this.init = function() {
        $('.container.main').css({
            'padding':0, 'margin':0, 'width':'100%'
        });
        $('.map-builder .map').css({
            'width': this.width / 2,
            'margin-left': this.width / 2,
            'height': this.height / 2,
            'margin-top': this.height / 2,
            'position':'relative',
        }).addClass($('#map-name').val());
        $('.export').on('click', function() { Map.Game.export(); });
        $('.go-top').on('click', function() { Map.Game.go(0); });
        $('.go-right').on('click', function() { Map.Game.go(1); });
        $('.go-bottom').on('click', function() { Map.Game.go(2); });
        $('.go-left').on('click', function() { Map.Game.go(3); });
        this.perRow = Math.floor(this.width / this.cellSize);
        this.perCol = Math.floor(this.height / this.cellSize);
        this.yStart = Math.round(this.perCol / 2) - this.perCol;
        this.xStart = Math.round(this.perRow / 2) - this.perRow;
        if (undefined !== window.mapBuilderValue) {
            this.fullMap = mapBuilderValue;
        }
        if (undefined !== window.mapBuilderOffsetX) {
            this.offsetX = window.mapBuilderOffsetX;
        }
        if (undefined !== window.mapBuilderOffsetY) {
            this.offsetY = window.mapBuilderOffsetY;
        }
        this.xStart += this.offsetX;
        this.yStart += this.offsetY;

        for(var y = this.yStart; y < this.yStart + this.perCol - 1; y++) {
            for(var x = this.xStart; x < this.xStart + this.perRow - 2; x++) {
                var cell = $('<div></div>').addClass('cell x-' + x + ' y-'+y).css({
                    'margin-top':(y - this.offsetY) * this.cellSize + 'px',
                    'margin-left':(x - this.offsetX) * this.cellSize + 'px',
                    'width': this.cellSize + 'px',
                    'height': this.cellSize + 'px'
                });
                cell.data('x' , x);
                cell.data('y' , y);
                cell.on('click', function() {
                    Map.Game.click($(this));
                });
                if (this.fullMap[y] !== undefined && this.fullMap[y][x] !== undefined) {
                    var cellValue = this.fullMap[y][x];
                    cell.html(cellValue);
                    cell.data('value', cellValue);
                    cell.addClass(this.getfirstPartOfValue(cellValue));
                }
                $('.map-builder .map').append(cell);
            }
        }
    };

    this.click = function (el) {
        $('.map .editor').each(function() {
            Map.Game.submit($(this));
        });
        var editor = $('<form><input type="text" /></form>').addClass('editor');
        var input = editor.find('input');
        var value = el.data('value');
        if (value) {
            el.html('');
            input.val(value);
        }
        el.append(editor);
        input.width(this.cellSize).focus();
        editor.on('submit', function(){Map.Game.submit($(this)); return false;});
    }

    this.submit = function(el) {
        var text = el.find('input').val();
        var cell = el.parent('.cell');
        var oldValue = cell.data('value');
        if (oldValue) {
            cell.removeClass(this.getfirstPartOfValue(oldValue));
        }
        cell.html(text);
        cell.data('value', text);
        cell.addClass(this.getfirstPartOfValue(text));
    }
    this.getAll = function () {
        var data = {};
        for(var y = this.yStart; y < this.yStart + this.perCol - 1; y++) {
            for(var x = this.xStart; x < this.xStart + this.perRow - 2; x++) {
                var cell = $('.cell.x-' + x + '.y-' + y);
                var value = cell.data('value');
                if (value) {
                    if (data[y] === undefined) {
                        data[y] = {};
                    }
                    data[y][x] = value;
                }
            }
        }
        return JSON.stringify(data);
    };
    this.export = function() {
        var string = this.getAll();
        Ajax.json('/Spellcraft/mapBuilder/save', {
            data: 'name=' + $('#map-name').val() + '&map=' + string,
            callBack : function(data){ Map.Game.callback(data) }
        });
    };
    this.go = function (d) {
        var string = this.getAll();
        Ajax.json('/Spellcraft/mapBuilder/save', {
            data: 'name=' + $('#map-name').val() + '&map=' + string,
            callBack : function(data){ Map.Game.move(d) }
        });
    };
    this.getfirstPartOfValue = function(value) {
        var byUnits = value.split('-');
        if (byUnits.length > 1) {
            value = byUnits[0];
        }
        var byObjects = value.split('+');
        if (byObjects.length > 1) {
            value = byObjects[0];
        }
        return value;
    };

    this.callback = function(data) {
        window.location = '/Spellcraft/mapBuilder/show/' + $('#map-name').val();
    }
    this.move = function(d) {
        var x = this.offsetX;
        var y = this.offsetY;
        switch (d) {
            case 0: y -= Math.round(this.perCol / 2); break;
            case 1: x += Math.round(this.perCol / 2); break;
            case 2: y += Math.round(this.perCol / 2); break;
            case 3: x -= Math.round(this.perCol / 2); break;
        }
        window.location = '/Spellcraft/mapBuilder/' + $('#map-name').val() + '?x=' + x + '&y=' + y;
    }

};

