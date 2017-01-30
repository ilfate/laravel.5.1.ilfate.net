


$(document).ready(function() {
    if (!$('body.WhiteHorde').length) {
        return;
    }

    WhiteHorde.CharacterHelper = function(game) {
        this.game = game;
        this.slotLocations = [
            'head', 'body', 'feet', 'hand', 'offHand'
        ];

        this.initCharacter = function (character) {
            var that = this;
            // character.click = function() {
            //     that.game.interface.vue.characterInfo = this;
            //     that.game.interface.vue.showCharacterInfo = true;
            // };
            character.show = false;
            for (var i in this.slotLocations) {
                var location = this.slotLocations[i];
                if (character.inventory[location]) {
                    character.inventory[location].character = character;
                    character.inventory[location].currentLocation = location;
                }
            }

            return character;
        }
    };
});