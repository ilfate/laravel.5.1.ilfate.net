


$(document).ready(function() {
    if (!$('body.WhiteHorde').length) {
        return;
    }

    WhiteHorde.Animations = function(game) {
        this.game = game;
        this.svg = $('<div></div>');

        this.particles = [];
        // this.particlesContainer = new PIXI.Container();
        this.particlesContainer = new PIXI.ParticleContainer(5000, {alpha: true});
        this.wind = [-15, 8];
        this.windSpeed = [-1, 1];
        this.newSprites = [];

        this.border = {
            x1 : 0,
            y1 : 0,
            x2 : this.game.screenWidth,
            y2 : this.game.screenHeight,
        };
        this.lastParticleOptions = {};
        this.animations = [];
        
        this.animate = function(sprite, properties, duration, callback) {
            var time = (new Date()).getTime();
            var current = {};
            for (var key in properties) {
                current[key] = sprite[key];
            }
            var animation = {
                sprite:sprite,
                properties:properties,
                current:current,
                duration:duration,
                end:duration + time,
                start:time,
                callback:callback,
            };
            this.animations.push(animation);
        };

        this.run = function() {
            if (this.newSprites) {
                for(var i in this.newSprites) {
                    var newSprite = this.newSprites.shift();
                    // this.particlesContainer.addChild(newSprite);
                    this.particlesContainer.addChild(newSprite);
                }
            }
            if (this.animations.length > 0) {
                var time = (new Date()).getTime();
                for (var i in this.animations) {
                    var animation = this.animations[i];
                    if (animation.end < time) {
                        this.setSpriteProperties(animation.sprite, animation.properties);
                        if (animation.callback) { animation.callback(); }
                        this.animations.splice(i, 1);
                    } else {
                        this.setPartialSpriteProperties(animation.sprite, animation.current, animation.properties,
                            1 - ((animation.end - time) / animation.duration)
                        );
                    }
                }
            }
            for(var i in this.particles) {
                var particle = this.particles[i];
                if (!particle.visible) continue;
                particle.sprite.x += particle.vx;
                particle.sprite.y += particle.vy;
                if (particle.maxAlpha !== undefined && particle.maxAlpha > particle.sprite.alpha) {
                     // info(particle.sprite.alpha);
                    particle.sprite.alpha = 1;
                    particle.sprite.visible = 1;
                }
                if (
                    particle.sprite.x + particle.width > this.border.x2
                    || particle.sprite.y + particle.width > this.border.y2
                    || particle.sprite.x + particle.width < this.border.x1
                    || particle.sprite.y + particle.width < this.border.y1
                ) {
                    if (particle.restart !== undefined) {
                        switch (true) {
                            case (particle.sprite.x + particle.width > this.border.x2): particle.sprite.x = this.border.x1; break;
                            case (particle.sprite.y + particle.width > this.border.y2): particle.sprite.y = this.border.y1; break;
                            case (particle.sprite.x < this.border.x1): particle.sprite.x = this.border.x2 - particle.width; break;
                            case (particle.sprite.y < this.border.y1): particle.sprite.y = this.border.y2 - particle.width; break;
                        }
                    } else {
                        particle.sprite.visible = false;
                        this.particlesContainer.removeChild(particle.sprite);
                        this.particles.splice(i, 1);
                        // info(particle.sprite.x);
                        continue;
                    }
                }
                if (particle.wind !== undefined && rand(0, 3) === 3) {
                    var low = (particle.reverceSizePercent / 2) + 0.5;
                     particle.vx = (this.wind[0] + rand (-3, 3)) * low;
                     particle.vy = (this.wind[1] + rand (-2, 2)) * low;
                }
            }
            if (rand(0,7) == 7) {
                this.updateWind();
            }
        };

        this.updateWind = function() {
            var newWindX = this.wind[0] + this.windSpeed[0];
            if (newWindX > 0) newWindX = 0;
            else if (newWindX < -30) newWindX = -30;
            var newWindY = this.wind[1] + this.windSpeed[1];
            if (newWindY < 5) newWindY = 5;
            else if (newWindY > 20) newWindY = 20;
            this.wind = [newWindX, newWindY];

            if (rand(0, 5) == 5) {
                var newWindSpeedX = rand(-1, 1);
                var newWindSpeedY = rand(-1, 1);
                // if (newWindSpeedX > 2) newWindSpeedX = 1;
                // if (newWindSpeedX < -2) newWindSpeedX = -1;
                // if (newWindSpeedY > 3) newWindSpeedY = 2;
                // if (newWindSpeedY < -3) newWindSpeedY = -2;
                this.windSpeed = [newWindSpeedX, newWindSpeedY];
                // info(this.wind);
            }
        };

        this.createParticle = function(options) {
            var circle = new PIXI.Graphics();
            if (options.color === undefined) { options.color = 0x9966FF}
            if (options.x1 === undefined) { options.x1 = this.border.x1}
            if (options.y1 === undefined) { options.y1 = this.border.y1}
            if (options.r1 === undefined) { options.r1 = 50}
            if (options.vx === undefined) { options.vx = 1}
            if (options.vy === undefined) { options.vy = 1}
            if (options.xs1 !== undefined && options.xs2 !== undefined) { options.x1 = rand(options.xs1, options.xs2)}
            if (options.ys1 !== undefined && options.ys2 !== undefined) { options.y1 = rand(options.ys1, options.ys2)}
            if (options.size === undefined) { options.size = 2}
            if (options.size1 !== undefined && options.size2 !== undefined) {
                options.size = rand(options.size1, options.size2);
                if (options.reverseSuperRand !== undefined) {
                    options.size = rand(options.size1, options.size);
                }
                circle.reverceSizePercent = ((options.size - options.size1) / (options.size2 - options.size1));
            }
            if (options.wind !== undefined) { circle.wind = true; }
            if (options.restart !== undefined) { circle.restart = true; }
            if (options.sizeAlpha !== undefined) {
                var addAlpha = (1 - options.sizeAlpha) * (1 - circle.reverceSizePercent);
                circle.alpha = options.sizeAlpha + addAlpha;
            }
            if (options.addSlowWithAlpha !== undefined) {
                circle.maxAlpha = circle.alpha;
                circle.alpha = 0;
            }

            
            circle.beginFill(options.color);
            var x = options.x1 + (options.r1 * Math.random()) - (options.r1 / 2);
            var y = options.y1 + (options.r1 * Math.random()) - (options.r1 / 2);
            circle.drawCircle(
                0,
                0,
                options.size);
            circle.endFill();
            circle.x = x;
            circle.y = y;
            circle.vx = options.vx;
            circle.vy = options.vy;
            var sprite = new PIXI.Sprite(this.game.renderer.generateTexture( circle));
            circle.sprite = sprite;
            sprite.x = circle.x;
            sprite.y = circle.y;
            sprite.alpha = circle.alpha;
            this.particles.push(circle);
            this.newSprites.push(sprite);
            this.lastParticleOptions = options;
        };

        this.changeParticlesNumber = function(newNumber) {
            if (this.particles.length < newNumber) {
                // this.lastParticleOptions.addSlowWithAlpha = true;
                for (var i = 0; i < newNumber - this.particles.length; i++) {
                    this.createParticle(this.lastParticleOptions);
                }
            } else if (this.particles.length > newNumber) {
                var numToDelete = this.particles.length - newNumber;
                for(var i in this.particles) {
                    this.particles[i].restart = undefined;
                    numToDelete--;
                    if (numToDelete <= 0) break;
                }
            }
        };
        this.addParticlesNumber = function (number) {
            this.changeParticlesNumber(this.particles.length + number);
        };
        
        this.resetBorder = function(duration) {
            this.animate(this.border, {
                x1 : 0,
                y1 : 0,
                x2 : this.game.screenWidth,
                y2 : this.game.screenHeight,
            }, duration);    
        };

        this.setSpriteProperties = function (sprite, properties) {
            for (var key in properties) {
                sprite[key] = properties[key];
            }
        };

        this.setPartialSpriteProperties = function (sprite, was, will, percent) {
            for (var key in will) {
                sprite[key] = was[key] + (will[key] - was[key]) * percent;
            }
        };
    };

    WhiteHorde.Interface = function(game) {
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
});