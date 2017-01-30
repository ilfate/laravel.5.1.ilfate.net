


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
            for (var i in this.buildings) {
                this.buildings[i].show = false;
                this.buildings[i].settlement = this;
                this.buildings[i].characters = {};
            }
            this.resources = this.game.whiteHordeData.settlement.resources;
            var allCharacters = this.game.whiteHordeData.characters;
            for (var i in allCharacters) {
                if (allCharacters[i].location == 0) {
                    // this.characters.push(allCharacters[i]);
                    this.characters.push(this.game.characterHelper.initCharacter(allCharacters[i]));
                }
            }
            this.items = this.game.whiteHordeData.settlement.inventory;
            for (var i in this.items) {
                this.items[i].character = false;
                this.items[i].currentLocation = false;
            }
            this.initBuildingSlots();
        };

        this.findItem = function (code) {
            for (var i in this.items) {
                if (this.items[i].code == code) {
                    return this.items[i];
                }
            }
            return false;
        };

        this.findCharacter = function (id) {
            for (var i in this.characters) {
                if (this.characters[i].id == id) {
                    return this.characters[i];
                }
            }
            return false;
        };

        this.findBuilding = function (id) {
            for (var i in this.buildings) {
                if (this.buildings[i].id == id) {
                    return this.buildings[i];
                }
            }
            return false;
        };
        
        this.hideBuildings = function() {
            for (var i in this.buildings) {
                this.buildings[i].show = false;
            }
        }

        this.initBuildingSlots = function() {
            for (var i in this.buildings) {
                for (var n in this.buildings[i].slots) {
                    var characterId = this.buildings[i].slots[n].characterId;
                    var slotName = this.buildings[i].slots[n].name;
                    if (characterId) {
                        this.buildings[i].characters[slotName] = this.findCharacter(characterId);
                    } else {
                        this.buildings[i].characters[slotName] = false;
                    }
                }
            }
        }

        this.updateResources = function (resources) {
            main:
            for (var i in resources) {
                var name = resources[i].name;
                for (var n in this.resources) {
                    if (this.resources[n].name == name) {
                        this.resources[n].value = resources[i].value;
                        this.resources[n].income = resources[i].income;
                        continue main;
                    }
                }
                this.resources.push(resources[i]);
            }
        }

    };
});