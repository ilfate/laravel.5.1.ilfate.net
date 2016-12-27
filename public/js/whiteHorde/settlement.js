


$(document).ready(function() {
    if (!$('body.WhiteHorde').length) {
        return;
    }

    WhiteHorde.Settlement = function(game) {
        this.game = game;
        this.buildings = [];
        this.items = ['sword', 'axe'];
        this.resources = [];
        this.characters = [];

        this.init = function () {
            var that = this;
            this.buildings = this.game.whiteHordeData.buildings;
            this.items     = this.game.whiteHordeData.settlement.inventory;
            this.resources = this.game.whiteHordeData.settlement.resources;
            var allCharacters = this.game.whiteHordeData.characters;
            for (var i in allCharacters) {
                if (allCharacters[i].location == 0) {
                    allCharacters[i].click = function() {
                        that.game.interface.vue.characterInfo = this;
                        that.game.interface.vue.showCharacterInfo = true;
                    };
                    this.characters.push(allCharacters[i]);
                }
            }
            // setTimeout(function () {
            //     info('UPDATED');
            //     that.buildings.push(that.buildings[0]);
            // }, 1500);
        };

    };
});