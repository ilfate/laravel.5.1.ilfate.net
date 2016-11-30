


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
        this.monsterSize = 24;
        
        this.center = [];
        this.debug=false;
        this.damageIndicator = false;

        this.margin = this.game.map.cellSize + this.game.map.cellMargin;
        this.diff = (this.game.map.cellSize - this.monsterSize) / 2 + this.game.map.cellMargin;

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
        };

        this.draw = function() {
            this.e = Crafty.e('2D, DOM, Color, Tween')
                .attr({x: this.x * this.margin + this.diff,
                    y: this.y * this.margin + this.diff,
                    w: this.monsterSize,
                    h: this.monsterSize})
                .color(this.color);
        };

        this.calculateMovement = function() {
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
                var diff = (this.monsterSize - size) / 2;
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
                var diff = (this.monsterSize - size) / 2;
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
            this.game.setMoney();
        };

        this.getDamageIndicatorSize = function() {
            var persent = 1 - (this.health / this.maxHealth);
            return Math.floor(this.monsterSize * persent);
        };

        this.attackBase = function() {

            this.game.nextMoves[this.y][this.x] = false;
            this.death();
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
});