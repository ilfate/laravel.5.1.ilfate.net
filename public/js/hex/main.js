/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */


function Hex () {

}
Hex = new Hex();

$(document).ready(function() {
    if ($('body.hex').length) {
        Hex.Game = new Hex.Game();
        Hex.Game.init();
    }
});


Hex.Game = function () {
    this.color = {
        'blue' : '#428BCA',
        'green' : '#069E2D',
        'yellow' : '#FFD416',
        'red' : '#F21616',
        'orange' : '#EF8354',
        'black' : '#584D3D',
        'white' : '#FFFFFF'
    };
	this.turnNumber = 1;
    this.patterns = {};
    this.currentPattern = {};

    this.init = function() {
        $('.hexagon-click-area').on('click', function() {
            Hex.Game.action($(this).parent());
        });
        $('.hexagon-click-area').on('mouseenter', function() {
            Hex.Game.hover($(this), true);
            //$(this).parent().find('.hexagon').addClass('hover');
        });
        $('.hexagon-click-area').on('mouseleave', function() {
            Hex.Game.hover($(this), false);
            //$(this).parent().find('.hexagon.hover').removeClass('hover');
        });
    }

    this.action = function(el) {
        if (el.find('.cell').length > 0) {
            this.buildWall(el);
        }

    }

    this.buildWall = function (el) {
        var x = el.data('x');
        var y = el.data('y');
        Ajax.json('/hex/action', {
            data: 'type=buildWall&x=' + x + '&y=' + y,
            callBack : function(data){ Hex.Game.callback(data) }
        });
    }

    this.callback = function(data) {
        if (data.action) {
            switch (data.action) {
                case 'buildWall':
                    this.buildWallAnimation(data.updates);
                    break;
            }
        }
    }

    this.buildWallAnimation = function (updates) {
        for (var y in updates.lasers) {
            for (var x in updates.lasers[y]) {
                var gun = $('.hexagon-container.x_' + x + '.y_' + y);
                for (var d in updates.lasers[y][x]) {
                    var laser = gun.find('.gun_' + d + ' .gun');
                    laser.animate({
                        width: updates.lasers[y][x][d]
                    }, {
                        duration:400,
                        complete: function() {}
                    });

                }
            }
        }
        if (updates.walls) {

            for (var k in updates.walls) {
                var wall = updates.walls[k];
                var cell = $('.hexagon-container.x_' + wall.x + '.y_' + wall.y + ' .hexagon');
                if (wall.status == 'new') {
                    cell.removeClass('cell').addClass('wall').addClass(wall.class);
                } else {
                    cell.removeClass('wall-0 wall-1 wall-2 wall-0-1 wall-0-2 wall-1-2 wall-0-1-2')
                        .addClass(wall.class);
                }
            }
        }
    }

    this.setPatterns = function(patterns) {
        this.patterns = patterns;
        this.setPattern(patterns[0]);
    }
    this.setPattern = function(pattern) {
        this.currentPattern = pattern;
    }
    this.hover = function(el, isAdd) {
        if (!this.currentPattern) {
            el.parent().find('.hexagon').addClass('hover');
        } else {
            info(el);
            var x = el.data('x');
            var y = el.data('y');
            for (var k in this.currentPattern) {

                var cellInfo = this.currentPattern[k];
                info(cellInfo);
                var cell = $('.x_' + (x + cellInfo[0]) + '.y_' + (y + cellInfo[1]) + ' .hexagon');
                info(cell);
                if (isAdd) {
                    cell.addClass('hover');
                } else {
                    cell.removeClass('hover');
                }
            }
        }
    }

}

Hex.Cell = function(data) {
    this.x = data.x;
    this.y = data.y;
    this.type = 'normal';
    this.doors = data.doors;

    this.render = function(target) {
        var temaplate = $('#template-cell').html();
        Mustache.parse(temaplate);
        var doors = '';
        var rendered = Mustache.render(temaplate, {
            'doors' : this.doors,
            'x' : this.x,
            'y' : this.y
        });
        var obj = $(rendered);
        $('.turn-area').append(obj);
        target.append(obj);
    }
}
