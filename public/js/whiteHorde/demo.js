


$(document).ready(function() {
    if (!$('body.WhiteHorde').length) {
        return;
    }

    WhiteHorde.Demo = function(game) {
        this.game = game;
        this.demoStep = 0;
        var that = this;
        this.demoConfig = {
            0:function() {that.initSnow(); that.addSnow({width:that.game.screenWidth, height:that.game.rem * 10, x:0, y : that.game.rem * 20})},
            // 15:function() {)},
            1:function() {that.addText('Imagine a land of snow', [that.game.rem * 5.5, that.game.rem * 10], {
                fontWeight:900, fontSize: 60, time1:3000,
                narrowBorder:{x1:that.game.rem * 5.5, y1 : that.game.rem * 9.5, x2 : that.game.rem * 45, y2 : that.game.rem * 15}
            })},
            15:function() {that.addText('A land where it never melts', [that.game.rem * 4.5, that.game.rem * 10], {
                fontWeight:900, fontSize: 70, time1:3000,
                narrowBorder:{x1:that.game.rem * 5, y1 : that.game.rem * 9, x2 : that.game.rem * 45, y2 : that.game.rem * 18}
            })},
            30:function() {that.addText('A land of cold despair and sharp ice', [that.game.rem * 5.5, that.game.rem * 10], {
                fontWeight:900, fontSize: 64, time1:3000,
                narrowBorder:{x1:that.game.rem * 5, y1 : that.game.rem * 9, x2 : that.game.rem * 45, y2 : that.game.rem * 18}
            })},
            45:function() {that.addMan({width:that.game.rem * 11, height:that.game.rem * 11, x:that.game.rem*5, y : that.game.rem * 11,
                alpha:0.5, time:2500, narrowBorder:{x1:that.game.rem*4, y1 : that.game.rem*10.5, x2 : that.game.rem*20, y2 : that.game.rem * 22}})},
            55:function() {that.addText('Man lives in this land', [that.game.rem * 6, that.game.rem * 10], {
                fontWeight:900, fontSize: 65, time1:3000,
                narrowBorder:{x1:that.game.rem * 5.5, y1 : that.game.rem * 9, x2 : that.game.rem * 45, y2 : that.game.rem * 14.5}
            })},
            70:function() {that.addMan({width:that.game.rem * 14, height:that.game.rem * 14, x:that.game.rem*11, y : that.game.rem * 8,
                alpha:0.65, time:2500, narrowBorder:{x1:that.game.rem*8, y1 : that.game.rem*7, x2 : that.game.rem*28, y2 : that.game.rem * 22}})},
            80:function() {that.addText('They survive', [that.game.rem * 10, that.game.rem * 10], {
                fontWeight:900, fontSize: 85, time1:3000,
                narrowBorder:{x1:that.game.rem * 5.5, y1 : that.game.rem * 9, x2 : that.game.rem * 45, y2 : that.game.rem * 14.5}
            })},
            95:function() {that.addMan({width:that.game.rem * 18, height:that.game.rem * 18, x:that.game.rem*15, y : that.game.rem * 3,
                alpha:0.8, time:2500, narrowBorder:{x1:that.game.rem*13, y1 : that.game.rem*2, x2 : that.game.rem*35, y2 : that.game.rem * 22}})},
            105:function() {that.addText('But is not the cold that makes live here a living hell', [that.game.rem * 4.5, that.game.rem * 10], {
                fontWeight:900, fontSize: 50, time1:3000,
                narrowBorder:{x1:that.game.rem * 4, y1 : that.game.rem * 9, x2 : that.game.rem * 47, y2 : that.game.rem * 16}
            })},
            120:function() {that.addEyes([that.game.rem * 18, that.game.rem * 10], {
                width:that.game.rem*16, height:that.game.rem*10, time1:3000, time2:1500,
                // narrowBorder:{x1:that.game.rem * 5.5, y1 : that.game.rem * 9, x2 : that.game.rem * 45, y2 : that.game.rem * 14.5}
            })},
            135:function() {that.addText('It`s the true masters of this land', [that.game.rem * 5.5, that.game.rem * 10], {
                fontWeight:900, fontSize: 64, time1:3000,
                narrowBorder:{x1:that.game.rem * 5, y1 : that.game.rem * 9, x2 : that.game.rem * 45, y2 : that.game.rem * 18}
            })},
            150:function() {that.addEyes([that.game.rem * 5, that.game.rem * 3], {
                width:that.game.rem*8, height:that.game.rem*5, time1:3000,
                // narrowBorder:{x1:that.game.rem * 5.5, y1 : that.game.rem * 9, x2 : that.game.rem * 45, y2 : that.game.rem * 14.5}
            })},
            152:function() {that.addEyes([that.game.rem * 2, that.game.rem * 14], {
                width:that.game.rem*8, height:that.game.rem*5, time1:3000,
                // narrowBorder:{x1:that.game.rem * 5.5, y1 : that.game.rem * 9, x2 : that.game.rem * 45, y2 : that.game.rem * 14.5}
            })},
            154:function() {that.addEyes([that.game.rem * 34, that.game.rem * 5], {
                width:that.game.rem*8, height:that.game.rem*5, time1:3000,
                // narrowBorder:{x1:that.game.rem * 5.5, y1 : that.game.rem * 9, x2 : that.game.rem * 45, y2 : that.game.rem * 14.5}
            })},
            156:function() {that.addEyes([that.game.rem * 38, that.game.rem * 16], {
                width:that.game.rem*8, height:that.game.rem*5, time1:3000,
                // narrowBorder:{x1:that.game.rem * 5.5, y1 : that.game.rem * 9, x2 : that.game.rem * 45, y2 : that.game.rem * 14.5}
            })},
            162:function() {that.addText('It`s the White Horde', [that.game.rem * 6, that.game.rem * 10], {
                fontWeight:900, fontSize: 65, time1:3000,
                //narrowBorder:{x1:that.game.rem * 5.5, y1 : that.game.rem * 9, x2 : that.game.rem * 45, y2 : that.game.rem * 14.5}
            })},
            178:function() {that.addMan({width:that.game.rem * 18, height:that.game.rem * 18, x:that.game.rem*15, y : that.game.rem * 3,
                alpha:0.8, time:2500, noHiding:true})},
            // 4:function() {that.borders(200, 200, 600, 400)},
            // 6:function() {that.snow(500)},
            // 10:function() {that.snow(50)},
        };
        
        this.init = function () {
            var imagesToLoad = [];
            for (var i in this.game.images) {
                imagesToLoad.push(this.game.images[i]);
            }
            var that = this;
            PIXI.loader
                .add(imagesToLoad)
                .load(function() {
                    that.game.runDemo();
                    that.game.state = that.game.game;
                    setTimeout(function () {

                        that.game.gameLoop();
                    }, 300);
                });

            this.game.renderer = PIXI.autoDetectRenderer(this.game.screenWidth, this.game.screenHeight);
            this.game.renderer.backgroundColor = 0xffffff;
            // this.renderer.backgroundColor = 0xdddddd;
            $('.demo-screen').append(this.game.renderer.view);
            // document.body.appendChild(this.renderer.view);
            this.game.stage = new PIXI.Container();
            this.game.layer1 = new PIXI.Container();
            this.game.layer2 = new PIXI.Container();
            this.game.stage.addChild(this.game.layer1);
            this.game.stage.addChild(this.game.layer2);
            this.game.stage.addChild(this.game.animations.particlesContainer);
            this.game.renderer.render(this.game.stage);
            // this.animations.initSVG();
            var maxGameSize = 546;
            var width = $(window).width();
            if (width < maxGameSize) {
                this.game.isMobile = true;
            }
        };
        
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