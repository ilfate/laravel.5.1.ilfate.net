


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
            $(this.e._element).on('click', function() {
                that.click($(this));
            })
        };

        this.click = function(el) {
            if (this.getTower()) {
                this.getTower().click(this);
                return;
            }
            if (this.isBuildable) {
                this.map.game.createTower(this.x, this.y, this.map.game.interface.selectedTowerType);
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