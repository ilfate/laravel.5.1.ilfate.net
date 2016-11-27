


$(document).ready(function() {
    if (!$('body.td').length) {
        return;
    }
    Houston.Game = function() {
        this.map = new Houston.Map(this);
        this.animations = new Houston.Animations(this);
        this.monsters = {};
        this.nextMoves = {};
        this.towers = {};
        this.money = 50;
        this.moneyE = {};
        this.textMargin = 0;
        this.color = {
            'brown' : '#5E412F',
            'grey' :  '#777777',
            'clay' :  '#FCEBB6',
            'red' :   '#FF8360',
            'green' : '#069E2D',
            'yellow': '#F0A830',
            'white' : '#ffffff',
            'purple': '#c700d6',
            'gold'  : '#F0A830',
        };
        this.towersConfig = {
            'basic' : {'color':this.color.green, 'price': 10, 'damage':1, 'attackPattern':[[-1,0], [0,-1], [1,0], [0,1]]},
            // 'basic2' : {'color':this.color.green, 'price': 40, 'damage':1, 'attackPattern':[[-4,0], [3,-1]]},
            // 'basic3' : {'color':this.color.green, 'price': 40, 'damage':3, 'attackPattern':[[-2,4], [3,-2]]},
        };
        this.descriptionCellSize = this.map.cellSize / 2;
        this.descriptionCellMargin = 1;
        this.monsterConfig = {
            'r1' : {'health': 1, 'moneyAward': 2, 'color':this.color.red},
            'b1' : {'health': 3, 'moneyAward': 10, 'color':this.color.purple},

        };
        this.selectedTowerType = 'basic';
        this.turnPause = 400;
        this.towerAttackPause = 50;
        this.moveTime = 200;
        this.gameRun = true;
        this.running = true;
        this.wave = 1;
        this.waveTurn = 0;
        this.turnsToSkip = 0;
        this.waveConfig = {
            1: {'name': '1HP', 'min': 2, 'max':2, 'types':['r1'], 'turns':5, 'skipTurns': 8},
            2: {'name': 'Boss 3HP', 'min': 1, 'max':1, 'types':['b1'], 'turns':1, 'skipTurns': 8},
        };


        this.init = function() {
            // var assetsObj = {
            //     "audio": {},
            //     "images": [],
            //     "sprites": {},
            // };
            // Crafty.load(assetsObj, // preload assets
            //     function() { //when loaded
            //     },
            //
            //     function(e) { //progress
            //     },
            //
            //     function(e) { //uh oh, error loading
            //     }
            // );
            this.animations.initSVG();
            Crafty.init(546,546, document.getElementById('td-start'));
            this.map.init();
            var that = this;
            $('.pause-button').on('click', function () {
                that.pauseToggle();
            });
            this.textMargin = (this.map.fieldSize + this.map.outerLines) / 2 * (this.map.cellSize + this.map.cellMargin)
            this.moneyE = Crafty.e('2D, DOM, Text')
                .attr({
                    x: this.textMargin,
                    y: this.textMargin
                });
            this.setMoney();
            this.displayTowersList();
        };

        this.action = function() {
            if (!this.gameRun) {
                this.running = false;
                return;
            }
            var isSpawn = true;
            var waveConfig = this.waveConfig[this.wave];
            if (!waveConfig) {
                info('Game end');
                return;
            }
            if (this.waveTurn >= waveConfig.turns) {
                //this wave is over. we need to start a new one
                if (waveConfig.skipTurns > 0) {
                    waveConfig.skipTurns--;
                    isSpawn = false;
                } else {
                    this.newWave();
                }
            }
            this.waveTurn++; 
            this.monsterTurn();
            var game = this;
            setTimeout(function(){
                if (isSpawn) {
                    game.spawnTime();
                }
                game.towersTurn();
                setTimeout(function(){ game.action() }, game.turnPause);
            }, this.towerAttackPause + this.moveTime);

        };

        this.monsterTurn = function() {
            this.nextMoves = {};
            if (rand(0, 1)) {
                for (var y in this.monsters) {
                    for (var x in this.monsters[y]) {
                        if (this.monsters[y][x]) {
                            this.monsters[y][x].calculateMovement();
                        }
                    }
                }
            } else {
                for(y = this.map.lastCell; y >= 0; y --) {
                    for(x = this.map.lastCell; x >= 0; x --) {
                        if (this.monsters[y] && this.monsters[y][x]) {
                            this.monsters[y][x].calculateMovement();
                        }
                    }
                }
            }
            for(var y in this.nextMoves) {
                for(var x in this.nextMoves[y]) {
                    if (this.nextMoves[y][x] && this.nextMoves[y][x].nextX !== null) {
                        this.nextMoves[y][x].activate();
                    }
                }
            }
            this.monsters = this.nextMoves;
        };

        this.towersTurn = function() {
            for(var y in this.towers) {
                for(var x in this.towers[y]) {
                    if (this.towers[y][x]) {
                        this.towers[y][x].activate();
                    }
                }
            }
        };
        
        this.spawnTime = function() {
            // this.waveTurn++;
            var config = this.waveConfig[this.wave];
            if (!config) return;
            // if (this.waveTurn > config.turns) {
                //this.newWave();
                //return this.spawnTime();
            // }
            var monsterToSpawn = rand(config.min, config.max);
            if (monsterToSpawn < 1) return;
            for(var i = 0; i < monsterToSpawn; i++) {
                this.createMonster(array_rand(config.types));
            }
        };

        this.newWave = function() {
            this.waveTurn = 0;
            this.wave++;
            var config = this.waveConfig[this.wave];
            $('.wave-indicator').html('Wave '+ this.wave + ' (' + config.name + ')')
                .css({color:this.color.red, 'font-size':22, 'border-color':this.color.white})
                .animate({
                    color:this.color.gold, 'font-size':14,
                    'border-color':this.color.clay
                }, 600);
            if (config.newTower !== undefined) {
                this.towersConfig[config.newTower].disabled = false;
                this.displayTowersList();
            }
            if (this.waveConfig[this.wave + 1] === undefined) {
                this.loadNextWave();
            }
        };
        this.loadNextWave = function() {
            var game = this;
            Ajax.json('/td/load/wave', {
                data: 'number=' + (this.wave + 1),
                callBack : function(data){ game.callback(data) }
            });
        };
        
        this.callback = function(data) {
            if (data.error !== undefined) {
                info(data.error);
                return;
            }
            if (data.waves !== undefined) {
                for(var num in data.waves) {
                    this.waveConfig[num] = data.waves[num];
                }
            }
            if (data.monsters !== undefined) {
                for(var num in data.monsters) {
                    if (this.monsterConfig[num] === undefined) {
                        this.monsterConfig[num] = data.monsters[num];
                    }
                }
            }
            if (data.towers !== undefined) {
                for(var num in data.towers) {
                    if (this.towersConfig[num] === undefined) {
                        this.towersConfig[num] = data.towers[num];
                        this.towersConfig[num].disabled = true;
                    }
                }
            }
        };

        this.pauseToggle = function() {
            if (this.gameRun) {
                this.gameRun = false;
                $('.pause-button').html('Play');
            } else {
                this.gameRun = true;
                $('.pause-button').html('Pause');
                if (!this.running) {
                    this.running = true;
                    this.action();
                }
            }
        };

        this.setMoney = function () {
            this.moneyE.text(this.money);
            var size = this.map.cellSize * 1.95;
            if (this.money >= 100 && this.money < 1000) {
                size = this.map.cellSize * 1.3;
            } else if (this.money >= 1000 && this.money < 10000) {
                size = this.map.cellSize;
            } else if (this.money >= 10000) {
                size = this.map.cellSize * 0.78;
            }
            if (this.money < 10) {
                this.moneyE.attr({
                    x: this.textMargin + this.map.cellSize / 2});
            } else {
                this.moneyE.attr({
                    x: this.textMargin});
            }
            this.moneyE.textFont({
                size: size + 'px',
                weight: 'bold',
                'lineHeight': this.map.cellSize * 2 + 'px'
            });
        };
        
        this.createMonster = function(type) {
            // if (x === undefined || y === undefined) {
                var x = 0;
                var y = 0;
                if (rand(0, 1)) {
                    if (rand(0, 1)) {
                        x = 0;
                    } else {
                        x = this.map.lastCell;
                    }
                    y = rand(0, this.map.lastCell);
                } else {
                    if (rand(0, 1)) {
                        y = 0;
                    } else {
                        y = this.map.lastCell;
                    }
                    x = rand(0, this.map.lastCell);
                }
            // }
            if (this.monsters[y] === undefined) {
                this.monsters[y] = {};
            }
            if (this.monsters[y][x]) {
                return false;
            }
            this.monsters[y][x] = new Houston.Monster(this, x, y, type);
            this.monsters[y][x].init();
            this.monsters[y][x].draw();
        };
        
        this.createTower = function(x, y, type) {
            if (!this.towersConfig[type]) {
                return;
            }
            var config = this.towersConfig[type];
            if (config.price > this.money) {
                return;
            }
            if (this.monsters[y] === undefined) {
                this.monsters[y] = {};
            }
            if (this.towers[y] === undefined) {
                this.towers[y] = {};
            }
            if (this.monsters[y][x] || this.towers[y][x]) {
                return false;
            }
            this.towers[y][x] = new Houston.Tower(this, x, y);
            this.towers[y][x].setConfig(config);
            this.towers[y][x].init();
            this.towers[y][x].draw();
        };
        this.setMonster = function(monster, x, y) {
            if (this.monsters[y] === undefined) {
                this.monsters[y] = {};
            }
            this.monsters[y][x] = monster;
        };

        this.addNextMove = function(x, y, monster) {
            if (this.nextMoves[y] === undefined) {
                this.nextMoves[y] = {};
            }
            if (this.nextMoves[y][x]) {
                return false;
            }
            this.nextMoves[y][x] = monster;
            return true;
        };
        this.getMonster = function(x, y) {
            if (!this.monsters[y] || !this.monsters[y][x]) {
                return false;
            }
            return this.monsters[y][x];
        };
        this.displayTowersList = function() {
            var template = $('#template-tower-description').html();
            var container = $('.towers-list');
            container.html('');
            Mustache.parse(template);
            for(var i in this.towersConfig) {
                var config = this.towersConfig[i];
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
                    map.append($('<div class="description-cell" style="margin: '+ y + 'px 0 0 ' + x + 'px ">' + config.damage + '</div>'))
                }
                grid[0][0] = true;
                map.append($('<div class="description-cell-center" style="background-color:' + config.color +'"></div>'))
                map.css({
                    'margin':Math.abs(minY) * (this.descriptionCellSize + this.descriptionCellMargin) + 'px 0 0 ' + Math.abs(minX) * (this.descriptionCellSize + this.descriptionCellMargin) + 'px',
                    'width':(maxX + 1) * (this.descriptionCellSize + this.descriptionCellMargin),
                    'height':(maxY + 1) * (this.descriptionCellSize + this.descriptionCellMargin),
                });
                var height = Math.max((maxY + Math.abs(minY) + 1), 3) * (this.descriptionCellSize + this.descriptionCellMargin);
                obj.find('.price').css({'height':height, 'line-height': height+'px'});
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
                container.append(obj);
            }
        }
        
    };
    var game = new Houston.Game();
    game.init();
    game.action();
});