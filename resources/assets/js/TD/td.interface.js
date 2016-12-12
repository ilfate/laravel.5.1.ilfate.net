


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

    Houston.Interface = function(game) {
        this.game = game;
        this.textMargin = 0;
        this.activeCell = false;
        this.descriptionCellSize = this.game.map.cellSize / 2;
        this.descriptionCellMargin = 1;
        this.selectedTowerType = 'Tbasic';
        this.moneyE = {};
        this.gamePausedByHowToPlay = false;
        this.moneyAnimationRuning = false;


        this.init = function() {

            var that = this;
            $('.pause-button').on('click', function () {
                that.pauseToggle();
            });
            $('.back-to-games-list-button').on('click', function () {
                that.redirectBackToGamesList();
            });
            $('.hambuger-button').on('click', function () {
                that.openMenu();
            });
            $('.restart-button').on('click', function () {
                that.game.restartGame();
            });
            $('.save-game-button').on('click', function () {
                that.game.saveGame();
            });
            $('.speed-up-button').on('click', function () {
                that.speedUp();
            });
            $('.how-to-play-button').on('click', function () {
                that.howToPlay();
            });
            $('.open-stats-button').on('click', function () {
                that.openStats();
            });
            $('.destroy-tower-button').on('click', function () {
                that.game.destroyTower();
            });
            $('.test-button').on('click', function () {
                that.game.wave = 14;
                that.game.startGame();
                that.game.loadNextWave();
            });
            this.textMargin = (this.game.map.fieldSize + this.game.map.outerLines) 
                / 2 * (this.game.map.cellSize + this.game.map.cellMargin);
            this.moneyE = Crafty.e('2D, DOM, Text')
                .attr({
                    x: this.textMargin,
                    y: this.textMargin
                });
            this.setMoney();
            this.displayTowersList();
            if (localStorage.getItem("immediateStart") === 'true') {
                localStorage.removeItem("immediateStart");
                this.game.startGame(1);
                return;
            } else {
                $('.start-overlay .start').show();
            }
            if (this.game.loadedGame) {
                // we have saved game here...
                $('.button-container.load').show();
            }
            this.displayStartOverlay();
        };
        
        this.displayStartOverlay = function() {
            var that = this;
            $('.start-game-button').on('click', function () {
                that.game.startGame();
            })
            $('.load-game-button').on('click', function () {
                that.game.loadGame(true);
            })
        };

        this.pauseToggle = function() {
            if (this.game.gameRun) {
                this.game.gameRun = false;
                $('.pause-button').html('Play');
                this.game.saveGame();
            } else {
                this.game.gameRun = true;
                $('.pause-button').html('Pause');
                if (!this.game.running) {
                    this.game.running = true;
                    this.game.action();
                }
            }
        };

        this.redirectBackToGamesList = function() {
            this.game.saveGame();
            window.location = '/Games';
        };

        this.setMoney = function () {
            this.moneyE.text(this.game.money);
            var size = this.game.map.cellSize * 1.95;
            if (this.game.money >= 100 && this.game.money < 1000) {
                size = this.game.map.cellSize * 1.3;
            } else if (this.game.money >= 1000 && this.game.money < 10000) {
                size = this.game.map.cellSize;
            } else if (this.game.money >= 10000) {
                size = this.game.map.cellSize * 0.78;
            }
            if (this.game.money < 10) {
                this.moneyE.attr({
                    x: this.textMargin + this.game.map.cellSize / 2});
            } else {
                this.moneyE.attr({
                    x: this.textMargin});
            }
            this.moneyE.textFont({
                size: size + 'px',
                weight: 'bold',
                'lineHeight': this.game.map.cellSize * 2 + 'px'
            });
        };

        this.moneyBlink = function() {
            if (this.moneyAnimationRuning) return;
            this.moneyAnimationRuning = true;
            var el = $(this.moneyE._element);
            var that = this;
            el.animate({
                color: this.game.color.red
            }, {duration:100, complete:function () {
                el.animate({
                    color: that.game.color.brown
                }, {duration:450, complete:function () {
                    that.moneyAnimationRuning = false;
                }});
            }});

        };
        

        this.displayTowersList = function() {
            var template = $('#template-tower-description').html();
            var container = $('.towers-list');
            container.html('');
            Mustache.parse(template);
            for(var i in this.game.towersConfig) {
                var config = this.game.towersConfig[i];
                if (config.disabled !== undefined && config.disabled) {
                    continue;
                }
                var rendered = Mustache.render(template, {
                    'price': config.price, 
                    'pattern': config.attackPattern, 
                    'damage': config.damage,
                    'name':i
                });
                var obj = $(rendered);
                var map = obj.find('.attack-map');
                var minX = 0;
                var minY = 0;
                var maxX = 0;
                var maxY = 0;
                var grid = {0:{}};
                for(var n in config.attackPattern) {
                    var cell = config.attackPattern[n];
                    var x = cell[0] * (this.descriptionCellSize + this.descriptionCellMargin);
                    var y = cell[1] * (this.descriptionCellSize + this.descriptionCellMargin);
                    if (cell[0] < minX) minX = cell[0];
                    if (cell[0] > maxX) maxX = cell[0];
                    if (cell[1] < minY) minY = cell[1];
                    if (cell[1] > maxY) maxY = cell[1];
                    if (!grid[cell[1]]) grid[cell[1]] = [];
                    grid[cell[1]][cell[0]] = true;
                    var damageEl = $('<div class="description-cell" style="margin: '+ y + 'px 0 0 ' + x + 'px ">' + config.damage + '</div>');
                    if (config.damage > 99) {
                        damageEl.css({'font-size':'0.5rem'});
                    }
                    map.append(damageEl);
                }
                grid[0][0] = true;
                map.append($('<div class="description-cell-center" style="background-color:' + config.color +'"></div>'))
                map.css({
                    'margin':Math.abs(minY) * (this.descriptionCellSize + this.descriptionCellMargin) 
                        + 'px 0 0 ' + Math.abs(minX) * (this.descriptionCellSize + this.descriptionCellMargin) + 'px',
                    'width':(maxX + 1) * (this.descriptionCellSize + this.descriptionCellMargin),
                    'height':(maxY + 1) * (this.descriptionCellSize + this.descriptionCellMargin),
                });
                var height = Math.max((maxY + Math.abs(minY) + 1), 3) 
                    * (this.descriptionCellSize + this.descriptionCellMargin) + this.descriptionCellMargin;
                obj.find('.price').css({'height':height, 'line-height': height+'px'});
                if (config.price > 99) {
                    obj.find('.price').css({'width':'2rem'});
                }
                for(var y = minY; y <= maxY; y ++) {
                    for(var x = minX; x <= maxX; x ++) {
                        if (!grid[y]) grid[y] = [];
                        if (!grid[y][x]) {
                            var cellX = x * (this.descriptionCellSize + this.descriptionCellMargin);
                            var cellY = y * (this.descriptionCellSize + this.descriptionCellMargin);
                            map.append($('<div class="description-cell-empty" style="margin: '+ cellY + 'px 0 0 ' + cellX + 'px "></div>'));
                        }
                    }
                }
                if (i === this.selectedTowerType) {
                    obj.addClass('selected');    
                }
                var game = this;
                obj.on('click', function() {
                    game.towerDescriptionClick($(this));
                });
                container.append(obj);
            }
        };

        this.towerDescriptionClick = function(el) {
            var towerList = $('.towers-list');
            if (!towerList.hasClass('upgrade')) {
                if (el.hasClass('selected')) return;
                $('.tower-description.selected').removeClass('selected');
                el.addClass('selected');
                this.selectedTowerType = el.data('type');
            } else {
                // tower is selected and we need to update it.
                var newType = el.data('type');
                var x = this.activeCell.x;
                var y = this.activeCell.y;
                var tower = this.game.towers[y][x];
                if (tower.type == newType) {
                    info('same type');return;
                }
                if (!this.game.validateTowerBuild(x, y, newType, true)) {
                    info('tower not validated');
                    return;
                }
                if (!this.game.isLocalDevelopment) {
                    ga('send', 'event', 'GamePlay', 'upgradeTower', 'TD', 1);
                }
                var config = this.game.towersConfig[newType];
                tower.update(config, newType).deselectTower();
                this.hideTowerUpgrade();
            }
        };

        this.showTowerUpgrade = function() {
            var towerList = $('.towers-list');
            towerList.addClass('upgrade');
            $('.selection-zone .upgrade-container').show();
        };

        this.hideTowerUpgrade = function() {
            var towerList = $('.towers-list');
            towerList.removeClass('upgrade');
            $('.selection-zone .upgrade-container').hide();
        };
        
        this.openMenu = function() {
            $('.hidden-menu').toggle();
        };
        
        this.howToPlay = function() {
            if (this.game.gameStarted && this.game.gameRun) {
                this.pauseToggle();
                this.gamePausedByHowToPlay = true;
            }
            if ($('.how-to-play-overlay').length > 0) {
                $('.how-to-play-overlay').slideDown();
                return;
            }
            var template = $('#how-to-play-overlay').html();
            Mustache.parse(template);
            var rendered = Mustache.render(template, {});
            var obj = $(rendered);
            $('.content-container').prepend(obj);
            var that = this;
            $('.how-to-play-overlay .close-button').on('click', function() {
                that.closeHowToPlay();
            });
        };

        this.closeHowToPlay = function() {
            if (this.game.gameStarted && this.gamePausedByHowToPlay) {
                this.gamePausedByHowToPlay = false;
                this.pauseToggle();
            }
            $('.how-to-play-overlay').slideUp();
        };
        
        this.showEndScreen = function() {
            var time = 800;
            var overlay = $('.start-overlay');
            overlay.css({'background-color':this.game.color.blue});
            overlay.find('.end').show();
            if (this.game.wave < 5) {
                overlay.find('.good').hide();
                overlay.find('.bad').show();
            } else {
                $('.start-overlay .waves-survived-number').html(this.game.wave);
            }
            overlay.slideDown(time);
            $('.wave-status, #td-start, .selection-zone, .pause-button').animate({opacity:0}, time);
        };
        
        this.openStats = function () {
            var that = this;
            Ajax.json('/td/getStats', {
                callBack : function(data){ that.addStatsToOverlay(data); }
            });
                        
        };

        this.addStatsToOverlay = function (data) {
            var that = this;
            var template = $('#stats-overlay').html();
            Mustache.parse(template);
            var rendered = Mustache.render(template, {stats:data.stats});
            var obj = $(rendered);
            $('.content-container').prepend(obj);
            $('.stats-overlay .close-button').on('click', function() {
                that.closeStats();
            });
        };

        this.closeStats = function () {
            $('.stats-overlay').slideUp(function(){$('.stats-overlay').remove();})
        };

        this.speedUp = function () {
            if (this.game.turnPause == 500) {
                this.game.turnPause = 250;
                this.game.moveTime = 125;
                $('.speed-up-button').html('Slow down');
            } else {
                this.game.turnPause = 500;
                this.game.moveTime = 200;
                $('.speed-up-button').html('Speed up');
            }
        }
        
    };
    // game.action();
});