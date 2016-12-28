


$(document).ready(function() {
    if (!$('body.WhiteHorde').length) {
        return;
    }

    WhiteHorde.Settlement = function(game) {
        this.game = game;
        this.buildings = [];
        this.items = [];
        this.resources = [];
        this.characters = [];

        this.init = function () {
            var that = this;
            this.buildings = this.game.whiteHordeData.buildings;
            this.resources = this.game.whiteHordeData.settlement.resources;
            var allCharacters = this.game.whiteHordeData.characters;
            for (var i in allCharacters) {
                if (allCharacters[i].location == 0) {
                    // this.characters.push(allCharacters[i]);
                    this.characters.push(this.game.characterHelper.initCharacter(allCharacters[i]));
                }
            }
            this.items = this.game.whiteHordeData.settlement.inventory;
                
            // setTimeout(function () {
            //     info('UPDATED');
            //     that.buildings.push(that.buildings[0]);
            // }, 1500);
        };

    };
});