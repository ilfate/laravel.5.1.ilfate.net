


$(document).ready(function() {
    if (!$('body.td').length) {
        return;
    }


    Houston.Map = function(game) {
        this.game = game;  
        this.cellSize = 32;
        this.fieldSize = 12;
        this.cellMargin = 2;
        this.outerLines = 2;
        this.lastCell = this.fieldSize + this.outerLines * 2 - 1;
        this.midCell = Math.floor(this.lastCell / 2);
        this.field = {};
        this.grid = new PF.Grid(this.lastCell + 1, this.lastCell + 1);
        this.gridNoTowers = new PF.Grid(this.lastCell + 1, this.lastCell + 1);
        this.finders = [
            new PF.AStarFinder(),
            new PF.AStarFinder({
                heuristic: PF.Heuristic.chebyshev
            }),
            new PF.AStarFinder({
                heuristic: PF.Heuristic.euclidean
            }),
            new PF.AStarFinder({
                heuristic: PF.Heuristic.octile
            })
        ];
        this.inavailableCenters = {};

        this.init = function() {
            var fieldWithLine = this.fieldSize + this.outerLines;
            var half = fieldWithLine / 2;
            for(var y = 0; y < this.fieldSize + this.outerLines * 2; y++) {
                for(var x = 0; x < this.fieldSize  + this.outerLines * 2; x++) {
                    var color = this.game.color.brown;
                    var cell = new Houston.Cell(x, y, this);
                    if (x < this.outerLines || y < this.outerLines || x >= fieldWithLine || y >= fieldWithLine) {
                        color = this.game.color.grey;
                        cell.isBuildable = false;
                    } else if (x >= half && x < half + 2 && y >= half && y < half + 2) {
                        color = this.game.color.clay;
                        cell.isBuildable = false;
                    }
                    if (this.field[y] === undefined) {
                        this.field[y] = {};
                    }
                    this.field[y][x] = cell;
                    cell.e = this.drawCell(
                        x * (this.cellSize + this.cellMargin)+ this.cellMargin,
                        y * (this.cellSize + this.cellMargin)+ this.cellMargin,
                        color
                    );
                    cell.setUp();

                }
            }
            // info(this.getPath(0,0,6,6));
        };
        this.getPath = function(x1, y1, x2, y2) {
            var finder = array_rand(this.finders);
            return finder.findPath(x1, y1, x2, y2, this.grid.clone());
        };
        this.getPathWithoutTowers = function(x1, y1, x2, y2) {
            return this.finders[0].findPath(x1, y1, x2, y2, this.gridNoTowers.clone());
        };
        this.drawCell = function(x,y,color) {
            var e = Crafty.e('2D, DOM, Color, Mouse')
                .attr({x: x,
                    y: y,
                    w: this.cellSize,
                    h: this.cellSize})
                .color(color);
            return e;
        };
        this.setMonster = function(x, y, isPassable) {
            this.field[y][x].setMonster(isPassable);
        };
        this.setTower = function(x, y, isPassable) {
            this.field[y][x].setTower(isPassable);
        };
        
        this.getClosestCenter = function(x, y) {
            var rx = this.midCell;
            var ry = this.midCell;
            if (x > this.midCell) {
                rx += 1;
            }
            if (y > this.midCell) {
                ry += 1;
            }
            return [rx, ry];
        }
    };
});