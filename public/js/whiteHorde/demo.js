


$(document).ready(function() {
    if (!$('body.WhiteHorde').length) {
        return;
    }

    WhiteHorde.Demo = function(game) {
        this.game = game;
        this.initSnow = function() {
            var vx = -15;
            var vy = 8;
            for (var i = 0; i < 500; i ++) {
                this.game.animations.createParticle({
                    color: 0xFFFFFF,
                    wind: true,
                    restart: true,
                    size1: 2,
                    size2: 10,
                    reverseSuperRand:true,
                    sizeAlpha: 0.45,
                    xs1:0,
                    xs2:this.game.screenWidth,
                    ys1:0,
                    ys2:this.game.screenHeight,
                    vx: vx,// + rand(-2, 1),
                    vy: vy,// + rand(-1, 2),
                });
            }
        };

        this.addSnow = function(options) {
            var snowSprite = new PIXI.Sprite(PIXI.loader.resources[this.game.images.snow].texture);
            snowSprite.width = options.width;
            snowSprite.height = options.height;
            snowSprite.x = options.x;
            snowSprite.y = options.y;
            this.game.layer2.addChild(snowSprite);
        };

        this.addMan = function(options) {
            var manSprite = new PIXI.Sprite(PIXI.loader.resources[this.game.images.man].texture);
            manSprite.width = options.width;
            manSprite.height = options.height;
            manSprite.x = options.x;
            manSprite.y = options.y;
            manSprite.alpha = 0;

            this.game.animations.animate(manSprite, {alpha:options.alpha}, options.time);
            var that = this;
            if (options.noHiding === undefined) {
                setTimeout(function () {
                    if (options.narrowBorder !== undefined) {
                        that.game.animations.animate(that.game.animations.border, options.narrowBorder, options.time, function () {
                            that.game.animations.resetBorder(1500);
                        });
                    }
                    that.game.animations.animate(manSprite, {alpha: 0}, options.time);
                }, options.time);
            }
            // this.stage.addChild(man0Sprite);
            this.game.layer1.addChild(manSprite);
        };

        this.addText = function (text, position, options) {
            if (options.fontSize === undefined) { options.fontSize = 60;}
            if (options.fontWeight === undefined) { options.fontWeight = 400;}
            var style = new PIXI.TextStyle({
                fontWeight: options.fontWeight,
                fontSize: options.fontSize,
                wordWrapWidth:900,
                wordWrap: true,
                align: 'center'
            });
            var message = new PIXI.Text(
                text,
                {fontFamily: "Arial", fill: "black",}
            );
            message.setStyle(style);
            message.alpha = 0;
            this.game.animations.animate(message, {alpha:1}, options.time1);

            message.position.set(position[0], position[1]);
            this.game.layer1.addChild(message);
            var that = this;
            setTimeout(function () {
                if (options.narrowBorder !== undefined) {
                    that.game.animations.animate(that.game.animations.border, options.narrowBorder, options.time1, function () {
                        that.game.animations.resetBorder(1500);
                    });
                }
                that.game.animations.animate(message, {alpha:0}, options.time1);
            }, options.time1);
        };

        this.addEyes = function (position, options) {

            var shadow = new PIXI.Sprite(PIXI.loader.resources[this.game.images.shadow2].texture);
            shadow.width = options.width;
            shadow.height = options.height;
            shadow.alpha = 0;
            this.game.animations.animate(shadow, {alpha:0.7}, options.time1);
            var eyes = new PIXI.Sprite(PIXI.loader.resources[this.game.images.eyes].texture);
            eyes.width = options.width / 2;
            eyes.height = options.width / 14;
            eyes.alpha = 0;
            this.game.animations.animate(eyes, {alpha:1}, options.time1);

            shadow.position.set(position[0], position[1]);
            eyes.position.set(position[0] + options.width / 4, position[1] + options.width / 4);
            this.game.layer1.addChild(shadow);
            this.game.layer1.addChild(eyes);
            var that = this;
            if (options.time2 !== undefined) {
                setTimeout(function () {
                    if (options.narrowBorder !== undefined) {
                        that.game.animations.animate(that.game.animations.border, options.narrowBorder, options.time1, function () {
                            that.game.animations.resetBorder(1500);
                        });
                    }
                    that.game.animations.animate(shadow, {alpha: 0}, options.time1);
                    that.game.animations.animate(eyes, {alpha: 0}, options.time1);
                }, options.time1 + options.time2);
            }
        };

        this.borders = function (x1,y1,x2,y2) {
            this.game.animations.border = {
                x1:x1,
                x2:x2,
                y1:y1,
                y2:y2,
            }
        }
    };

    
});