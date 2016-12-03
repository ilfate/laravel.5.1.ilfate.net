


$(document).ready(function() {
    if (!$('body.td').length) {
        return;
    }
    Houston.Game = function() {
        this.map = new Houston.Map(this);
        this.animations = new Houston.Animations(this);
        this.interface = new Houston.Interface(this);
        this.monsters = {};
        this.nextMoves = {};
        this.towers = {};
        this.money = 50;
        this.color = {
            'brown' : '#5E412F',
            'blue'  : '#529BCA',
            'red' :   '#FF8360',
            'green' : '#069E2D',
            'greenDark' : '#07B26A',
            'greenVeryDark' : '#1C3923',
            'grey' :  '#777777',
            'clay' :  '#FCEBB6',
            'yellow': '#F0A830',
            'white' : '#ffffff',
            'purple': '#c700d6',
            'gold'  : '#F0A830',
            'orange': '#F07818',
        };
        this.towersConfig = {
            'Tbasic' : {'image':'Tbasic', 'color':this.color.green, 'price': 10, 'damage':1, 'attackPattern':[[-1,0], [0,-1], [1,0], [0,1]]},
            // 'TSniper1' : {'image':'TSniper', 'rotate': 1, 'color':this.color.green, 'price': 1, 'damage':1, 'attackPattern':[[1,-1],[1,0],[1,1]]},
            // 'TSniper2' : {'image':'TSniper', 'rotate': 2, 'color':this.color.green, 'price': 1, 'damage':1, 'attackPattern':[[1,-1],[1,0],[1,1]]},
            // 'basic3' : {'color':this.color.green, 'price': 40, 'damage':3, 'attackPattern':[[-2,4], [3,-2]]},
        };
        this.monsterConfig = {
            'r1' : {'health': 1, 'moneyAward': 2, 'color':this.color.red},
            'b1' : {'health': 3, 'moneyAward': 10, 'color':this.color.purple},

        };
        this.turnPause = 500;
        this.towerAttackPause = 250;
        this.moveTime = 200;
        this.gameRun = true;
        this.running = true;
        this.isLocalDevelopment = false;
        this.loadedGame = false;
        this.gameEnded = false;
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
            this.isLocalDevelopment = $('#isLocalDevelopment').length > 0;
            Crafty.sprite(24, "images/game/td/towers.png", {
                Tbasic:[0,0], Tdiagonal:[1,0], TSniper:[2,0], Tfork:[3,0], Thorse:[4,0],
                TBaseBlue:[3,1], TBlueBolder:[4,1], TBlueCanon:[5,1],
            });
            this.animations.initSVG();
            Crafty.init(546,546, document.getElementById('td-start'));
            this.map.init();
            this.loadGame();
            this.interface.init();
        };
        


        this.startGame = function(time) {
            if (!time) time = 800;
            $('.start-overlay').slideUp(time);
            $('.wave-status, #td-start, .selection-zone, .pause-button').animate({opacity:1}, time);
            var game = this;
            setTimeout(function() {
                game.action();
            }, 1000);
        };

        this.action = function() {
            if (!this.gameRun) {
                this.running = false;
                return;
            }
            if (this.gameEnded) {
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
            var game = this;
            this.waveTurn++;
            this.monsterTurn();
            setTimeout(function(){
                game.monsterTurn(true);
            }, this.towerAttackPause + this.moveTime);

            setTimeout(function(){
                if (isSpawn) {
                    game.spawnTime();
                }
                game.towersTurn();
                setTimeout(function(){ game.action() }, game.turnPause);
            }, this.towerAttackPause + this.moveTime * 2);

        };

        this.monsterTurn = function(onlyFast) {
            this.nextMoves = {};
            if (rand(0, 1)) {
                for (var y in this.monsters) {
                    for (var x in this.monsters[y]) {
                        if (this.monsters[y][x] && (onlyFast && this.monsters[y][x].fast || !onlyFast)) {
                            this.monsters[y][x].calculateMovement();
                        } else if (this.monsters[y][x] && onlyFast) {
                            this.addNextMove(x, y, this.monsters[y][x]);
                        }
                    }
                }
            } else {
                for(y = this.map.lastCell; y >= 0; y --) {
                    for(x = this.map.lastCell; x >= 0; x --) {
                        if (this.monsters[y] && this.monsters[y][x] && (onlyFast && this.monsters[y][x].fast || !onlyFast)) {
                            this.monsters[y][x].calculateMovement();
                        } else if (this.monsters[y] && this.monsters[y][x] && onlyFast) {
                            this.addNextMove(x, y, this.monsters[y][x]);
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
            this.saveGame();
            var config = this.waveConfig[this.wave];
            if (!config) {
                info('no new wave');return;
            }
            $('.wave-indicator.current').html('Wave '+ this.wave + ' (' + config.name + ')')
                .css({color:this.color.red, 'background-color':this.color.orange})
                .animate({
                    color:this.color.gold,
                    'background-color':this.color.brown
                }, 1600);

            if (config.newTower !== undefined) {
                this.towersConfig[config.newTower].disabled = false;
                this.interface.displayTowersList();
            }
            if (this.waveConfig[this.wave + 1] === undefined) {
                this.loadNextWave();
                $('.wave-indicator.next .next-description').html('Loading...');
            } else {
                var nextConf = this.waveConfig[this.wave + 1];
                $('.wave-indicator.next .next-description').html(nextConf.name)
            }
        };
        this.loadNextWave = function(modefire, callback) {
            if (!modefire) modefire = 0;
            if (!callback) callback = function () {};
            var game = this;
            Ajax.json('/td/load/wave', {
                data: 'number=' + (this.wave + 1 + modefire),
                callBack : function(data){ game.callback(data); callback(); }
            });
        };
        
        this.callback = function(data) {
            if (data.error !== undefined) {
                info(data.error);
                return;
            }
            if (data.waves !== undefined) {
                for(var num in data.waves) {
                    if (num == this.wave + 1) {
                        var name = data.waves[num].name;
                        $('.wave-indicator.next .next-description').html(name)
                    }
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
            if (!this.validateTowerBuild(x, y, type)) {
                return;
            }
            var config = this.towersConfig[type];
            this.towers[y][x] = new Houston.Tower(this, x, y);
            this.towers[y][x].setConfig(config, type);
            this.towers[y][x].init();
            this.towers[y][x].draw();
            if (!this.isLocalDevelopment) {
                ga('send', 'event', 'GamePlay', 'buildTower', 'TD', 1);
            }
        };
        this.validateTowerBuild = function(x, y, type, isOverrride) {
            if (!this.towersConfig[type]) {
                info('tower type does not exist');return false;
            }
            var config = this.towersConfig[type];
            if (config.price > this.money) {
                info('Not enough money');return false;
            }
            if (this.monsters[y] === undefined) {
                this.monsters[y] = {};
            }
            if (this.towers[y] === undefined) {
                this.towers[y] = {};
            }
            if (this.monsters[y][x] || (this.towers[y][x] && !isOverrride)) {
                return false;
            }
            return true;
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

        this.saveGame = function() {
            var wasRunning = this.gameRun;
            this.gameRun = false;

            var data = {};
            var monsterExport = [];
            for (var y in this.monsters) {
                for (var x in this.monsters[y]) {
                    if (this.monsters[y][x]) {
                        monsterExport.push(this.monsters[y][x].export());
                    }
                }
            }
            var towersExport = [];
            for (var y in this.towers) {
                for (var x in this.towers[y]) {
                    if (this.towers[y][x]) {
                        towersExport.push(this.towers[y][x].export());
                    }
                }
            }
            var data = {
                'towers':towersExport,
                'monsters':monsterExport,
                'money':this.money,
                'wave':this.wave,
                'waveTurn':this.waveTurn,
                'turnsToSkip':this.turnsToSkip,
            };

            localStorage.setItem("tdSave", JSON.stringify(data));

            this.gameRun = wasRunning;
        };

        this.loadGame = function(isFullLoad) {
            var savedData = JSON.parse(localStorage.getItem("tdSave"));
            if (savedData != null && savedData != undefined) {
                this.loadedGame = savedData; 
            }
            if (isFullLoad) {
                if (!this.isLocalDevelopment) {
                    ga('send', 'event', 'GamePlay', 'loadGame', 'TD', 1);
                }
                this.money = this.loadedGame.money;
                this.wave = this.loadedGame.wave;
                this.waveTurn = this.loadedGame.waveTurn;
                this.turnsToSkip = this.loadedGame.turnsToSkip;
                for (var i in this.loadedGame.towers) {
                    var tower = this.loadedGame.towers[i];
                    var x = tower.x;
                    var y = tower.y;
                    if (this.towers[y] === undefined) this.towers[y] = {};
                    this.towers[y][x] = new Houston.Tower(this, x, y);
                    this.towers[y][x].import(tower);
                    this.towers[y][x].draw();
                }
                for (var i in this.loadedGame.monsters) {
                    var monster = this.loadedGame.monsters[i];
                    var x = monster.x;
                    var y = monster.y;
                    var type = monster.type;
                    if (this.monsters[y] === undefined) {
                        this.monsters[y] = {};
                    }
                    this.monsters[y][x] = new Houston.Monster(this, x, y, type);
                    this.monsters[y][x].import(monster);
                    this.monsters[y][x].draw();
                }
                var that = this;
                this.loadNextWave(-1, function () {
                    that.startGame();
                });
            }
        };

        this.stopGame = function() {
            this.gameEnded = true;
        };

        this.deleteSavedGame = function() {
            localStorage.removeItem("tdSave");
        };

        this.restartGame = function() {
            this.deleteSavedGame();
            localStorage.setItem("immediateStart", 'true');
            window.location.reload();
        };
        
    };
    var game = new Houston.Game();
    game.init();
    // game.action();
});