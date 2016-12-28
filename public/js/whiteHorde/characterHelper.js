


$(document).ready(function() {
    if (!$('body.WhiteHorde').length) {
        return;
    }

    WhiteHorde.CharacterHelper = function(game) {
        this.game = game;


        this.initCharacter = function (character) {
            var that = this;
            character.click = function() {
                that.game.interface.vue.characterInfo = this;
                that.game.interface.vue.showCharacterInfo = true;
            };
            character.addItem = function(ev, character) {
                info("DROP!!!");
                var that = this;
                ev.preventDefault();
                var type = ev.dataTransfer.getData("type");
                var itam = false;
                for (var i in that.game.settlement.items) {
                    if (that.game.settlement.items[i].name == type) {
                        item = that.game.settlement.items[i];
                        break;
                    }
                }
                if (!item) return;
                item.q -= 1;
                character.items[item.location] = item;
                info(item);
                //ev.target.appendChild(document.getElementById(data));
            };
            return character;
        }
    };
});