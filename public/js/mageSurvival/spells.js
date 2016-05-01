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
           case 'ExplodingBees': this.fire.startStandartFire() ; break;
           case 'ButthurtJump': this.fire.startStandartFire() ; break;
           case 'FireLady': this.fire.startStandartFire() ; break;
           case 'FaceCanon': this.fire.startStandartFire() ; break;
           case 'PhoenixStrike': this.fire.startStandartFire() ; break;
           case 'RainOfFire': this.fire.startStandartFire() ; break;
           case 'IceCrown': this.water.startIceCrown() ; break;
           default:
               isSpellAnimated = false;
               info('No start animation for "' + name + '"');
        }
        if (isSpellAnimated) {
            this.spellAnimationRunning = true;
            this.currentSpellName = name;
            $('.battle-border .mage path.hand').hide();
            $('.battle-border .mage path.active-hand').show();
        }
    };
    this.iterate = function(name) {
        switch (name) {
            case 'Fireball': this.fire.iterateFireball() ; break;
            case 'FireNova': this.fire.iterateStandartFire() ; break;
            case 'ExplodingBees': this.fire.iterateStandartFire() ; break;
            case 'ButthurtJump': this.fire.iterateStandartFire() ; break;
            case 'FireLady': this.fire.iterateStandartFire() ; break;
            case 'FaceCanon': this.fire.iterateStandartFire() ; break;
            case 'PhoenixStrike': this.fire.iterateStandartFire() ; break;
            case 'RainOfFire': this.fire.iterateStandartFire() ; break;
            case 'IceCrown': this.water.iterateIceCrown() ; break;
            default:
                info('No iteration animation for "' + name + '"');
        }
    };
    this.continue = function(name) {
        switch (name) {
            case 'Fireball': this.fire.finishFireball(this.currentSpellData); break;
            case 'FireNova': this.fire.finishFireNova(this.currentSpellData); break;
            case 'ExplodingBees': this.fire.finishExplodingBees(this.currentSpellData); break;
            case 'ButthurtJump': this.fire.finishButthurtJump(this.currentSpellData); break;
            case 'FireLady': this.fire.finishExplodingBees(this.currentSpellData); break;
            case 'FaceCanon': this.fire.finishFaceCanon(this.currentSpellData); break;
            case 'PhoenixStrike': this.fire.finishPhoenixStrike(this.currentSpellData); break;
            case 'RainOfFire': this.fire.finishRainOfFire(this.currentSpellData); break;
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
        this.clearAnimationField();
    };
    this.clearAnimationField = function() {
        $('.battle-border .mage path.hand').show();
        $('.battle-border .mage path.active-hand').hide();
        $('.animation-field').html('');
        this.savedData = [];
        this.currentSpellName = '';
        this.isSecondPartWaiting = false;
        this.currentSpellData = {};
        this.spellAnimationRunning = false;
    }

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

    this.angle_trunc = function(a) {
        while (a < 0.0) {
            a += Math.PI * 2
        }
        return a
    };
    this.getDistanceBetweenTwoDots = function(x1, y1, x2, y2) {
        var deltaY = y2 - y1;
        var deltaX = x2 - x1;
        var rad = this.angle_trunc(Math.atan2(deltaY, deltaX));
        //var rad = Math.atan2(data.targetY, data.targetX); // In radians
        //Then you can convert it to degrees as easy as:
        var deg = rad * (180 / Math.PI);
        var distance = Math.sqrt(Math.pow(deltaX, 2) + Math.pow(deltaY, 2));
        return [deg, distance];
    }

};

