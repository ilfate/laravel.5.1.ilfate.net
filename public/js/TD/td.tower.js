


$(document).ready(function() {
    if (!$('body.td').length) {
        return;
    }


    Houston.Tower = function(game, x ,y) {
        this.game = game;  
        this.x = x;
        this.y = y;
        this.towerSize = 24;
        this.price = 10;
        this.color = this.game.color.green;
        this.attackPattern = [
            [-1,0], [0,-1], [1,0], [0,1]
        ];
        this.damage = 1;
        this.e = {};
        this.margin = this.game.map.cellSize + this.game.map.cellMargin;
        this.diff = (this.game.map.cellSize - this.towerSize) / 2 + this.game.map.cellMargin;

        this.game.map.setTower(this.x, this.y, false);
        
        this.init = function() {
            this.game.money -= this.price;
            this.game.setMoney();
        };
        
        this.activate = function() {
            var monster = {};
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
                }
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
                tower.e = Crafty.e('2D, DOM, Color, Tween')
                    .attr({
                        x: tower.x * tower.margin + tower.game.map.cellSize / 2,
                        y: tower.y * tower.margin + tower.game.map.cellSize / 2,
                        w: 0,
                        h: 0,
                        //rotation:-90
                    }).origin("center")
                    .color(tower.color);
                tower.e.tween({
                    x: tower.x * tower.margin + tower.diff,
                    y: tower.y * tower.margin + tower.diff,
                    w: tower.towerSize,
                    h: tower.towerSize,
                    rotation:0
                }, 100);
            }, 300);
        };

        this.setConfig = function(config) {
            if (config.damage !== undefined) {
                this.damage = config.damage;
            }
            if (config.attackPattern !== undefined) {
                this.attackPattern = config.attackPattern;
            }
            if (config.price !== undefined) {
                this.price = config.price;
            }
        };
        
        this.destroy = function() {
            this.game.map.setTower(this.x, this.y, true);
            this.game.towers[this.y][this.x] = false;
        }
    };
});