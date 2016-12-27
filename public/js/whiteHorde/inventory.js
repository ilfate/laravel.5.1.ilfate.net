


$(document).ready(function() {
    if (!$('body.WhiteHorde').length) {
        return;
    }

    WhiteHorde.Inventory = function(game) {
        this.game = game;

        this.init = function () {
            this.inventory = this.game.whiteHordeData.settlement.inventory;
        };

        this.showInventory = function () {
            
        }
    };
});