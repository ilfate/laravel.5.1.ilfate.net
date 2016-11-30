


$(document).ready(function() {
    if (!$('body.td').length) {
        return;
    }


    Houston.Cell = function(x, y, map) {
        this.map = map;
        this.x = x;
        this.y = y;
        this.e = {};
        this.isPassable = true;
        this.isBuildable = true;

        this.setUp = function() {
            var that = this;
            this.e.bind('Click', function () {
                that.click($(this));
            });
        };

        this.click = function(el) {
            if (this.getTower()) {
                this.getTower().click(el, this);
                return;
            }
            if (this.isBuildable) {
                this.map.game.createTower(this.x, this.y, this.map.game.selectedTowerType);
            }
        };



        this.setMonster = function(isPassable) {
            this.isPassable = isPassable;
            this.map.grid.setWalkableAt(this.x, this.y, isPassable);
            this.map.gridNoTowers.setWalkableAt(this.x, this.y, isPassable);
        };

        this.setTower = function(isPassable) {
            this.isPassable = isPassable;
            this.map.grid.setWalkableAt(this.x, this.y, isPassable);
        };

        this.getTower = function() {
            if (this.map.game.towers[this.y] && this.map.game.towers[this.y][this.x]) {
                return this.map.game.towers[this.y][this.x];
            }
            return null;
        }
    };
});