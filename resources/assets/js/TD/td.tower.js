


$(document).ready(function() {
    if (!$('body.td').length) {
        return;
    }

    Houston.Monster = function(game, x ,y, type) {
        this.game = game;
        this.x = x;
        this.y = y;
        this.type = type;
        this.nextX = 0;
        this.nextY = 0;
        this.path = [];
        this.e = {};
        //this.cell = this.game.map.field[y][x];
        this.diagonal = false;
        this.flying = false;
        this.fast = false;
        this.resurecting = false;
        this.health = 5;
        this.maxHealth = 5;
        this.moneyAward = 1;

        this.center = [];
        this.debug=false;
        this.damageIndicator = false;

        this.margin = this.game.map.cellSize + this.game.map.cellMargin;
        this.diff = (this.game.map.cellSize - this.game.monsterSize) / 2 + this.game.map.cellMargin;

        //this.cell.setPassible(false);
        //this.game.map.setMonster(this.x, this.y, false);
        this.init = function() {
            this.config = this.game.monsterConfig[type];
            if (this.config.health !== undefined) {
                this.health = this.config.health;
                this.maxHealth = this.config.health;
            }
            if (this.config.moneyAward !== undefined) {
                this.moneyAward = this.config.moneyAward;
            }
            if (this.config.color !== undefined) {
                this.color = this.config.color;
            }
            if (this.config.diagonal !== undefined) {
                this.diagonal = this.config.diagonal;
            }
            if (this.config.fast !== undefined) {
                this.fast = this.config.fast;
            }
            if (this.config.flying !== undefined) {
                this.flying = this.config.flying;
            }
            if (this.config.resurecting !== undefined) {
                this.resurecting = this.config.resurecting;
            }
        };

        this.draw = function() {
            this.e = Crafty.e('2D, DOM, Color, Tween')
                .attr({x: this.x * this.margin + this.diff,
                    y: this.y * this.margin + this.diff,
                    w: this.game.monsterSize,
                    h: this.game.monsterSize})
                .color(this.color);
        };

        this.calculateMovement = function() {
            if (this.game.gameEnded) {
                return;
            }
            this.readyToKill = false;
            this.center = this.game.map.getClosestCenter(this.x, this.y);
            if (!this.diagonal) {
                this.path = this.game.map.getPath(this.x, this.y, this.center[0], this.center[1]);
            } else {
                this.path = this.game.map.getPathDiagonal(this.x, this.y, this.center[0], this.center[1]);
            }
            if (typeof this.path[1] === 'undefined') {
                this.path = this.game.map.getPathWithoutTowers(this.x, this.y, this.center[0], this.center[1]);
                this.readyToKill = true;
                // no path... this is bad. Let`s kill some walls.
            }
            this.nextX = this.path[1][0];
            this.nextY = this.path[1][1];
            if (this.debug) {console.log('path', this.nextX, this.nextY)}
            if (!this.game.addNextMove(this.nextX, this.nextY, this)) {
                this.cancelMove(false);
            }
        };

        this.cancelMove = function(wasAdded, cancelX, cancelY) {
            if (this.debug) {console.log('cancel', this.nextX, this.nextY, wasAdded)}
            if (this.nextX === null) {
                return;
            }
            if (wasAdded) {
                var monster = this.game.nextMoves[cancelY][cancelX];
                if (monster.x != this.x || monster.y != this.y) {
                    console.log('wrong monster deleted');
                    console.log(this.x, this.y, cancelX, cancelY);
                }
                this.game.nextMoves[cancelY][cancelX] = wasAdded;
            }
            this.nextX = null;
            this.nextY = null;

            if (!this.game.addNextMove(this.x, this.y, this)) {
                if (this.debug) {console.log('cancelFail', this.x, this.y)}
                // well here we have a conflict...
                this.game.nextMoves[this.y][this.x].cancelMove(this, this.x, this.y);
                // var result = this.game.addNextMove(this.x, this.y, this);
                // console.log(result, this.x, this.y);
            }
        };

        this.activate = function() {
            if (this.game.gameEnded) {
                return;
            }
            this.move(this.nextX, this.nextY);
            if (this.x == this.center[0] && this.y == this.center[1]) {
                return this.attackBase();
            }
        };

        this.move = function(x,y) {
            var newCell = this.game.map.field[y][x];
            if (!newCell.isPassable) {
                if (this.readyToKill) {
                    var tower = this.game.towers[y][x];
                    if (!tower) {
                        info('there is no tower to attack...');
                        return false;
                    }
                    tower.destroy();
                    return this.move(x, y);
                }
                info('this cell [' +x+ ', ' +y+'] is not passable');
                return false;
            }
            this.x = x;
            this.y = y;
            this.nextX = null;
            this.nextY = null;
            this.e.tween({x: this.x * this.margin + this.diff,
                y: this.y * this.margin + this.diff}, this.game.moveTime, "smoothStep");
        };

        this.death = function() {
            var that = this;
            setTimeout(function() {
                that.e.destroy();
            },this.game.moveTime);
            this.game.map.setMonster(this.x, this.y, true);
        };

        this.damage = function(value) {
            this.health -= value;
            if (this.health <= 0) {
                this.game.monsters[this.y][this.x] = false;
                this.award();
                this.death();
            } else if (!this.damageIndicator) {
                var size = this.getDamageIndicatorSize();
                var diff = (this.game.monsterSize - size) / 2;
                this.damageIndicator = Crafty.e('2D, DOM, Color, Tween, DamageIndicator')
                    .attr({
                        x: this.x * this.margin + this.diff + diff,
                        y: this.y * this.margin + this.diff + diff,
                        w: size,
                        h: size})
                    .color(this.game.color.yellow);
                this.e.attach(this.damageIndicator);
            } else {
                var size = this.getDamageIndicatorSize();
                var diff = (this.game.monsterSize - size) / 2;
                this.damageIndicator.tween({
                    x: this.x * this.margin + this.diff + diff,
                    y: this.y * this.margin + this.diff + diff,
                    w: size,
                    h: size
                }, 50, "smoothStep");
            }
        };

        this.award = function() {
            this.game.money += this.moneyAward;
            this.game.interface.setMoney();
        };

        this.getDamageIndicatorSize = function() {
            var persent = 1 - (this.health / this.maxHealth);
            return Math.floor(this.game.monsterSize * persent);
        };

        this.attackBase = function() {

            // this.game.nextMoves[this.y][this.x] = false;
            // this.death();
            this.game.stopGame();
        };

        this.export = function() {
            return {
                x: this.x,
                y: this.y,
                type: this.type,
                health: this.health,
                maxHealth: this.maxHealth,
                moneyAward: this.moneyAward,
                color: this.color,
                diagonal: this.diagonal,
                flying: this.flying,
                fast: this.fast,
                resurecting: this.resurecting,
            };
        };

        this.import = function(data) {
            this.x = data.x;
            this.y = data.y;
            this.type = data.type;
            this.health = data.health;
            this.maxHealth = data.maxHealth;
            this.moneyAward = data.moneyAward;
            this.color = data.color;
            this.diagonal = data.diagonal;
            this.flying = data.flying;
            this.fast = data.fast;
            this.resurecting = data.resurecting;
            this.config = this.game.monsterConfig[type];
        };
    };

    Houston.Tower = function(game, x ,y) {
        this.game = game;  
        this.type = '';
        this.x = x;
        this.y = y;
        
        this.price = 10;
        this.color = this.game.color.green;
        this.attackPattern = [
            [-1,0], [0,-1], [1,0], [0,1]
        ];
        this.cooldown = 0;
        this.cooldownTurnLeft = 0;
        this.targets = 0;
        this.rotate = 0;
        this.image = false;
        this.damage = 1;
        this.e = {};
        this.margin = this.game.map.cellSize + this.game.map.cellMargin;
        this.diff = (this.game.map.cellSize - this.game.towerSize) / 2 + this.game.map.cellMargin;

        this.game.map.setTower(this.x, this.y, false);
        
        this.init = function() {
            this.game.money -= this.price;
            this.game.interface.setMoney();
        };
        
        this.activate = function() {
            if (this.game.gameEnded) {
                return;
            }
            if (this.cooldownTurnLeft) {
                this.cooldownTurnLeft--;
                return;
            }
            var damageDone = false;
            var monster = {};
            var targets = 999;
            if (this.targets) {
                targets = this.targets;
            }
            for(var n in this.attackPattern) {
                var coord = this.attackPattern[n];

                if (monster = this.game.getMonster(this.x + coord[0], this.y + coord[1])) {
                    this.game.animations.shot({
                        x1:this.x, y1:this.y,
                        x2:this.x + coord[0], y2: this.y + coord[1],'time': 0.1,
                        'beamWidth': 10,
                        'segment1': ["100%", "100%"],
                        'segment2': ["0%", "100%"],
                        'delete':true,
                        'delay': 100,
                        'yesIWantToHaveBlinkBug': true,
                        'color': this.game.color.white});
                    monster.damage(this.damage);
                    targets--;
                    damageDone = true;
                }
                if (!targets) break;
            }
            if (this.cooldown && damageDone) {
                this.cooldownTurnLeft = this.cooldown;
            }
        };

        this.draw = function() {
            var from = (this.game.map.midCell) * (this.game.map.cellSize + this.game.map.cellMargin);
            var from2 = (this.game.map.midCell + 2) * (this.game.map.cellSize + this.game.map.cellMargin);
            this.game.animations.particles({
                min:this.price,
                max:this.price,
                time:400,
                color:this.color,
                size:3,
                fromX1: from, fromY1: from,
                fromX2: from2, fromY2: from2,
                toX1: this.x * this.margin + this.diff * 3, toY1: this.y * this.margin + this.diff * 3,
                toX2: (this.x + 1) * this.margin - this.diff * 3, toY2: (this.y + 1) * this.margin - this.diff * 3,
            });
            var tower = this;
            setTimeout(function() {
                var angle = 90 * tower.rotate;
                tower.e = Crafty.e('2D, DOM, Color, Tween, Image, ' + (tower.image ? tower.image : tower.type))
                    .attr({
                        x: tower.x * tower.margin + tower.game.map.cellSize / 2,
                        y: tower.y * tower.margin + tower.game.map.cellSize / 2,
                        w: 0,
                        h: 0,
                        //rotation:-90
                    }).color(tower.color);
                tower.e.origin(tower.game.towerSize / 2, tower.game.towerSize / 2);
                $(tower.e._element).on('click', function () {
                    var y = tower.y;
                    var x = tower.x;
                   tower.click(tower.game.map.field[y][x]);
                });
                tower.e.tween({
                    x: tower.x * tower.margin + tower.diff,
                    y: tower.y * tower.margin + tower.diff,
                    w: tower.game.towerSize,
                    h: tower.game.towerSize,
                    rotation:angle
                }, 150);
            }, 250);
        };

        this.click = function(cell) {
            var towerList = $('.towers-list');
            if (cell.e.has('ActiveCell') && towerList.hasClass('upgrade')) {
                this.game.interface.hideTowerUpgrade();
                this.deselectTower();
            } else if (!towerList.hasClass('upgrade')) {
                this.game.interface.showTowerUpgrade();
                cell.e.color(this.game.color.orange);
                cell.e.addComponent('ActiveCell');
                this.game.interface.activeCell = cell;
            } else if (towerList.hasClass('upgrade') && !cell.e.has('ActiveCell')) {
                Crafty('ActiveCell').removeComponent('ActiveCell').color(this.game.color.brown);
                cell.e.color(this.game.color.orange);
                cell.e.addComponent("ActiveCell");
                this.game.interface.activeCell = cell;
            }
            
        };

        this.deselectTower = function() {
            var cell = this.game.map.field[this.y][this.x];
            cell.e.color(this.game.color.brown);
            cell.e.removeComponent('ActiveCell');
            this.game.interface.activeCell = false;
            return this;
        };
        
        

        this.setConfig = function(config, type) {
            this.type = type;
            if (config.damage !== undefined) {
                this.damage = config.damage;
            }
            if (config.attackPattern !== undefined) {
                this.attackPattern = config.attackPattern;
            }
            if (config.price !== undefined) {
                this.price = config.price;
            }
            if (config.color !== undefined) {
                this.color = config.color;
            }
            if (config.targets !== undefined) {
                this.targets = config.targets;
            } else {
                this.targets = 0;
            }
            if (config.rotate !== undefined) {
                this.rotate = config.rotate;
            } else {
                this.rotate = 0;
            }
            if (config.image !== undefined) {
                this.image = config.image;
            } else {
                this.image = false;
            }
            if (config.cooldown !== undefined) {
                this.cooldown = config.cooldown;
            } else {
                this.cooldown = 0;
            }
        };

        this.update = function(config, type) {
            this.setConfig(config, type);
            this.init();
            this.e.destroy();
            this.draw();
            return this;
        };
        
        this.destroy = function() {
            this.game.map.setTower(this.x, this.y, true);
            this.game.towers[this.y][this.x] = false;
            this.e.destroy();
        };

        this.export = function() {
            return {
                x: this.x,
                y: this.y,
                type: this.type,
                attackPattern: this.attackPattern,
                damage: this.damage,
                price: this.price,
                color: this.color,
                cooldownTurnLeft: this.cooldownTurnLeft,
                cooldown: this.cooldown,
                targets: this.targets,
                rotate: this.rotate,
                image: this.image,
            };
        };

        this.import = function(data) {
            this.x = data.x;
            this.y = data.y;
            this.type = data.type;
            this.attackPattern = data.attackPattern;
            this.damage = data.damage;
            this.price = data.price;
            this.color = data.color;
            this.cooldownTurnLeft = data.cooldownTurnLeft;
            this.cooldown = data.cooldown;
            this.targets = data.targets;
            this.rotate = data.rotate;
            this.image = data.image;
        };
    };
});