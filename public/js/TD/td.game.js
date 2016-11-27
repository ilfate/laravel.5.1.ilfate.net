


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
            'brown': '#5E412F',
            'grey':  '#777777',
            'clay':  '#FCEBB6',
            'red':   '#FF8360',
            'green': '#069E2D',
            'yellow': '#F0A830',
            'white': '#ffffff',
        };
        this.towersConfig = {
            'basic' : {'price': 10, 'damage':1, 'attackPattern':[[-1,0], [0,-1], [1,0], [0,1]]}
        };
        this.monsterConfig = {
            'r1' : {'health': 1, 'moneyAward': 2},
            'b1' : {'health': 3, 'moneyAward': 10},
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
            1: {'min': 2, 'max':2, 'types':['r1'], 'turns':2, 'skipTurns': 0},
            2: {'min': 2, 'max':2, 'types':['r1'], 'turns':3, 'skipTurns': 7},
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
            this.wave++; info('wave ' + this.wave);
            this.loadNextWave();
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
        
    };
    var game = new Houston.Game();
    game.init();
    // game.createMonster(4,1);
    // game.createMonster(4,2);
    // game.createMonster(14,10);
    // game.createMonster(15,10);
    // game.createMonster(13,10);
    // game.createMonster(10,15);
    // game.createMonster(10,14);
    // game.createMonster(11,14);
    // game.createMonster(5,12);
    // game.createMonster(10,13);
    // game.createMonster(9,13);
    // game.createMonster(8,13);
    // game.createMonster(7,13);
    // game.createMonster(6,13);
    // game.createMonster(5,13);
    // game.createMonster();
    // game.createMonster();
    // game.createMonster();
    // game.createMonster();
    // game.createMonster();
    // game.createMonster();
    // game.createMonster();
    // game.createMonster();
    // game.createMonster();
    // game.createMonster();
    // game.createMonster();
    // game.createMonster();
    // game.createTower(5, 5);
    // game.createTower(6, 5);
    // game.createTower(7, 5);
    // game.createTower(8, 5);
    // game.createTower(9, 5);
    // game.createTower(10, 5);
    // game.createTower(10, 6);
    // game.createTower(10, 7);
    // game.createTower(10, 8);
    // game.createTower(10, 9);
    // game.createTower(5, 6);
    // game.createTower(5, 7);
    // game.createTower(5, 8);
    // game.createTower(5, 9);
    // game.createTower(5, 10);
    // game.createTower(6, 10);
    // game.createTower(7, 10);
    // game.createTower(8, 10);
    // game.createTower(10, 10);
    // game.createTower(10, 11);
    // game.createTower(10, 12);
    // game.createTower(9, 12);
    // game.createTower(8, 12);
    // game.createTower(7, 12);
    // game.createTower(6, 12);
    game.action();

    // Crafty.e('2D, DOM, Color, Fourway').attr({x: 0, y: 0, w: 100, h: 100}).color('#F00').fourway(200);
    //
    // Crafty.e('2D, Canvas, Color, Twoway, Gravity')
    //     .attr({x: 0, y: 0, w: 50, h: 50})
    //     .color('#F00')
    //     .twoway(200)
    //     .gravity('Floor');
    //
    // Crafty.e('Floor, 2D, Canvas, Color')
    //     .attr({x: 0, y: 250, w: 250, h: 10})
    //     .color('green');
    // var sq1 = Crafty.e("2D, Canvas, Color")
    //     .attr({x:10, y:10, w:30, h:30})
    //     .color("red");
    //
    // sq1.bind('EnterFrame', function(){
    //     this.rotation = this.rotation + 1;
    // });
});