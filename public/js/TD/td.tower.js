


$(document).ready(function() {
    if (!$('body.td').length) {
        return;
    }


    Houston.Tower = function(game, x ,y) {
        this.game = game;  
        this.type = '';
        this.x = x;
        this.y = y;
        this.towerSize = 24;
        this.price = 10;
        this.color = this.game.color.green;
        this.attackPattern = [
            [-1,0], [0,-1], [1,0], [0,1]
        ];
        this.cooldown = 0;
        this.cooldownTurnLeft = 0;
        this.targets = 0;
        this.damage = 1;
        this.e = {};
        this.margin = this.game.map.cellSize + this.game.map.cellMargin;
        this.diff = (this.game.map.cellSize - this.towerSize) / 2 + this.game.map.cellMargin;

        this.game.map.setTower(this.x, this.y, false);
        
        this.init = function() {
            this.game.money -= this.price;
            this.game.interface.setMoney();
        };
        
        this.activate = function() {
            if (this.game.gameEnded) {
                return;
            }
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
                }
                if (!targets) break;
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
                tower.e = Crafty.e('2D, DOM, Color, Tween, Image, ' + tower.type)
                    .attr({
                        x: tower.x * tower.margin + tower.game.map.cellSize / 2,
                        y: tower.y * tower.margin + tower.game.map.cellSize / 2,
                        w: 0,
                        h: 0,
                        //rotation:-90
                    }).origin("center")
                    .color(tower.color);
                if (tower.image) {
                    tower.e.image(tower.image);
                }
                tower.e.tween({
                    x: tower.x * tower.margin + tower.diff,
                    y: tower.y * tower.margin + tower.diff,
                    w: tower.towerSize,
                    h: tower.towerSize,
                    rotation:0
                }, 150);
            }, 250);
        };

        this.click = function(cellEl, cell) {
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
            }
        };

        this.update = function(config, type) {
            this.setConfig(config, type);
            this.init();
            this.e.color(this.color);
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
        };
    };
});