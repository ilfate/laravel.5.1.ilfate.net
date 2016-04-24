/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Spells = function (game) {
    this.game = game;
    this.currentSpellName = '';
    this.isSecondPartWaiting = false;
    this.spellAnimationRunning = false;
    this.currentSpellData = {};
    this.savedData = [];
    this.fire = {};
    this.water = {};
    this.init = function () {
        this.fire = new MageS.Spells.Fire(this.game, this);
        this.water = new MageS.Spells.Water(this.game, this);
    };

    this.cast = function(data) {

        if (this.spellAnimationRunning) {
            this.currentSpellData = data;
            this.isSecondPartWaiting = true;
        } else {
            info('there is no animation running for spell ' + data.spell);
            MageS.Game.animations.singleAnimationFinished();
        }
    };

    this.startCast = function(name) {
        var isSpellAnimated = true;
        switch (name) {
           case 'Fireball': this.fire.startFireball() ; break;
           case 'FireNova': this.fire.startStandartFire() ; break;
           //case 'FireLady': this.fire.startStandartFire() ; break;
           case 'ExplodingBees': this.fire.startStandartFire() ; break;
           case 'IceCrown': this.water.startIceCrown() ; break;
           default:
               isSpellAnimated = false;
               info('No start animation for "' + name + '"');
        }
        if (isSpellAnimated) {
            this.spellAnimationRunning = true;
            this.currentSpellName = name;
        }
    };
    this.iterate = function(name) {
        switch (name) {
            case 'Fireball': this.fire.iterateFireball() ; break;
            case 'FireNova': this.fire.iterateStandartFire() ; break;
            //case 'FireLady': this.fire.iterateStandartFire() ; break;
            case 'ExplodingBees': this.fire.iterateStandartFire() ; break;
            case 'IceCrown': this.water.iterateIceCrown() ; break;
            default:
                info('No iteration animation for "' + name + '"');
        }
    };
    this.continue = function(name) {
        switch (name) {
            case 'Fireball': this.fire.finishFireball(this.currentSpellData); break;
            case 'FireNova': this.fire.finishFireNova(this.currentSpellData); break;
            //case 'FireLady': this.fire.finishFireLady(this.currentSpellData); break;
            case 'ExplodingBees': this.fire.finishExplodingBees(this.currentSpellData); break;
            case 'IceCrown': this.water.finishIceCrown(this.currentSpellData); break;
            default:
                info('No last animation for "' + name + '"');
                MageS.Game.animations.singleAnimationFinished();
        }
    };
    this.tryToEndFirstPart = function() {
        if (this.isSecondPartWaiting)  {
            this.continue(this.currentSpellName);
        } else {
            this.iterate(this.currentSpellName);
        }
    };
    this.endSpellAnimation = function () {
        MageS.Game.animations.singleAnimationFinished();
        $('.animation-field').html('');
        this.savedData = [];
        this.currentSpellName = '';
        this.isSecondPartWaiting = false;
        this.currentSpellData = {};
        this.spellAnimationRunning = false;
    };

    this.createIcon = function(icon, color, rotate) {
        var iconEl = $(this.game.svg).find('#' + icon + ' path');
        var svg = $('<div class="svg animation"><svg class="svg-icon" viewBox="0 0 512 512"></svg></div>');
        svg.find('svg').append(iconEl.clone());
        if (color) {
            svg.addClass(color);
        }
        if (rotate) {
            svg.find('svg').rotate(rotate + 'deg');
        }
        return svg;
    };

};

