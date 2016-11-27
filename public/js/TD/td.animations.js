


$(document).ready(function() {
    if (!$('body.td').length) {
        return;
    }


    Houston.Animations = function(game) {
        this.game = game;
        this.svg = $('<div></div>');


        this.angle_trunc = function(a) {
            while (a < 0.0) {
                a += Math.PI * 2
            }
            return a
        };
        this.getDistanceBetweenTwoDots = function(x1, y1, x2, y2) {
            var deltaY = y2 - y1;
            var deltaX = x2 - x1;
            var rad = this.angle_trunc(Math.atan2(deltaY, deltaX));
            //var rad = Math.atan2(data.targetY, data.targetX); // In radians
            //Then you can convert it to degrees as easy as:
            var deg = rad * (180 / Math.PI);
            var distance = Math.sqrt(Math.pow(deltaX, 2) + Math.pow(deltaY, 2));
            return {distance:distance, deg:deg};
        };

        this.getCellCenter = function(coord) {
            var margin = this.game.map.cellSize + this.game.map.cellMargin;
            return coord * margin + (this.game.map.cellSize / 2)
        };
        
        this.shot = function(options) {
            if (!options) { options = {}; }
            var calculations = this.getDistanceBetweenTwoDots(options.x1, options.y1, options.x2, options.y2);
            calculations.deg -= 45;
            var side = calculations.distance / Math.sqrt(2);
            var e = Crafty.e('2D, DOM, Shot')
                .attr({ w: '1',
                        h:'1',
                    rotation:calculations.deg});

            var element = e._element;
            var svg  = this.getSvg('icon-bullet-lightning');
            var Jel = $(element).append(svg);

            Jel.css({'width':'1px','height':'1px', 'margin-left': this.getCellCenter(options.x1), 'margin-top': this.getCellCenter(options.y1)});
            Jel.find('svg').css({'width':side * this.game.map.cellSize, 'height':side * this.game.map.cellSize});
            var transform = ' rotate(' + calculations.deg +'deg)';
            element.style.transform = transform;

            var path = Jel.find('path');
            var baseBeamWidth = 10;
            if (options.beamWidth !== undefined) { baseBeamWidth = options.beamWidth; }
            var strokeWidth = (baseBeamWidth - length) / 10;
            path.css({'fill': 'none', 'stroke': options.color, 'stroke-width': strokeWidth + 'rem', 'stroke-opacity': 1});
            var pathEl = path[0];
            var segment = new Segment(pathEl);
            var time = 0.8;
            if (options.time !== undefined) { time = options.time}
            var segment1Start = "0";
            var segment1End = "0";
            var segment2Start = "100%";
            var segment2End = "150%";
            var isSegment3 = false;
            var time2 = 0;
            if (options.segment1 !== undefined) { segment1Start = options.segment1[0]; segment1End = options.segment1[1]; }
            if (options.segment2 !== undefined) { segment2Start = options.segment2[0]; segment2End = options.segment2[1]; }
            if (options.segment3 !== undefined) { isSegment3 = true; var segment3Start = options.segment3[0]; var segment3End = options.segment3[1]; time2 = options.time2 }
            if (options.yesIWantToHaveBlinkBug === undefined) {
                segment.draw(segment1Start, segment1End, 0);
            }
            var delay = 0;
            var delay2 = 0;
            if (options.delay !== undefined) { delay = options.delay; }
            if (options.delay2 !== undefined) { delay2 = options.delay2; }
            setTimeout(function() {
                if (options.yesIWantToHaveBlinkBug !== undefined) {
                    segment.draw(segment1Start, segment1End, 0);
                }
                segment.draw(segment2Start, segment2End, time);
            }, delay);
            if (isSegment3) {
                setTimeout(function () {
                    segment.draw(segment3Start, segment3End, time2);
                }, delay + (time * 1000) + delay2);
            }
            if (options.delete !== undefined) {
                setTimeout(function() {
                    e.destroy();
                }, delay + (time * 1000) + (time2 * 1000) + delay2)
            }
        };

        this.particles = function(options) {
            var number = rand(options.min, options.max);
            var particles = [];

            for(var i = 0; i < number; i++) {
                info('Particle');
                var x = rand(options.fromX1, options.fromX2);
                var y = rand(options.fromY1, options.fromY2);
                var particle = Crafty.e('2D, DOM, Tween, Color, Particle')
                    .attr({ w: options.size,
                        h: options.size,
                        x:x, y:y
                    }).color(options.color).origin("center");;
                var x2 = rand(options.toX1, options.toX2);
                var y2 = rand(options.toY1, options.toY2);
                particle.tween({
                    x: x2,
                    y: y2,
                    rotation:720}, options.time, "smoothStep");
                particles.push(particle);
            }
            setTimeout(function () {
                for(var i in particles) {
                    particles[i].destroy();
                }
            }, options.time)
        };

        this.initSVG = function() {
            //this.svgCallback = callback;
            var urls = {'svg' : '/images/game/td/td.svg'};
            //this.svgToLoad = Object.keys(urls).length;
            for (var i in urls) {
                this.loadSingleSvg(urls[i], i);
            }
        };
        this.loadSingleSvg = function(url, key) {
            var game = this;
            jQuery.get(url, function (data) {

                game.svg.append(jQuery(data).find('svg'));
                //MageS.Game.singleSvgLoadDone();
            }, 'xml');
        };
        this.getSvg = function (icon) {
            var iconEl = $(this.svg).find('#' + icon + ' path');
            var svg = $('<div class="svg animation"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="svg-icon" viewBox="0 0 512 512"></svg></div>');
            svg.find('svg').append(iconEl.clone());
            return svg;
        }
    };
});