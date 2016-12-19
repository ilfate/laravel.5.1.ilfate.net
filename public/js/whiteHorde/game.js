


$(document).ready(function() {
    if (!$('body.WhiteHorde').length) {
        return;
    }
    WhiteHorde.Game = function() {

        this.rem = 20;
        this.screenWidth = 980;
        this.screenHeight = 600;
        this.animations = new WhiteHorde.Animations(this);
        this.demo = new WhiteHorde.Demo(this);
        // this.interface = new Houston.Interface(this);
        // this.monsters = {};
        // this.nextMoves = {};
        // this.towers = {};
        // this.money = 50;
        this.stage = {};
        this.layer1 = {};
        this.renderer = {};
        this.color = {
            'brown' : '#5E412F',
            'blue'  : '#529BCA',
            'red' :   '#FF8360',
            'green' : '#069E2D',
            'greenDark' : '#07B26A',
            'greenVeryDark' : '#1C3923',
            'grey' :  '#777777',
            'clay' :  '#FCEBB6',
            'yellow': '#F0A830',
            'white' : '#ffffff',
            'purple': '#c700d6',
            'bordo': '#711F1F',
            'gold'  : '#F0A830',
            'orange': '#F07818',
        };

        this.state = this.game;

        this.images = {
            'man':"/images/game/WhiteHorde/path3338-3.png",
            'snow':"/images/game/WhiteHorde/snow.png",
            'shadow1':"/images/game/WhiteHorde/shadow1.png",
            'shadow2':"/images/game/WhiteHorde/shadow2.png",
            'eyes':"/images/game/WhiteHorde/eyes.png",
            'man2':"/images/game/tank1.jpg",
        };

        var that = this;
        this.demoConfig = {
            0:function() {that.demo.initSnow(); that.demo.addSnow({width:that.screenWidth, height:that.rem * 10, x:0, y : that.rem * 20})},
            // 15:function() {)},
            1:function() {that.demo.addText('Imagine a land of snow', [that.rem * 5.5, that.rem * 10], {
                fontWeight:900, fontSize: 60, time1:3000,
                narrowBorder:{x1:that.rem * 5.5, y1 : that.rem * 9.5, x2 : that.rem * 45, y2 : that.rem * 15}
            })},
            15:function() {that.demo.addText('A land where it never melts', [that.rem * 4.5, that.rem * 10], {
                fontWeight:900, fontSize: 70, time1:3000,
                narrowBorder:{x1:that.rem * 5, y1 : that.rem * 9, x2 : that.rem * 45, y2 : that.rem * 18}
            })},
            30:function() {that.demo.addText('A land of cold despair and sharp ice', [that.rem * 5.5, that.rem * 10], {
                fontWeight:900, fontSize: 64, time1:3000,
                narrowBorder:{x1:that.rem * 5, y1 : that.rem * 9, x2 : that.rem * 45, y2 : that.rem * 18}
            })},
            45:function() {that.demo.addMan({width:that.rem * 11, height:that.rem * 11, x:that.rem*5, y : that.rem * 11,
                alpha:0.5, time:2500, narrowBorder:{x1:that.rem*4, y1 : that.rem*10.5, x2 : that.rem*20, y2 : that.rem * 22}})},
            55:function() {that.demo.addText('Man lives in this land', [that.rem * 6, that.rem * 10], {
                fontWeight:900, fontSize: 65, time1:3000,
                narrowBorder:{x1:that.rem * 5.5, y1 : that.rem * 9, x2 : that.rem * 45, y2 : that.rem * 14.5}
            })},
            70:function() {that.demo.addMan({width:that.rem * 14, height:that.rem * 14, x:that.rem*11, y : that.rem * 8,
                alpha:0.65, time:2500, narrowBorder:{x1:that.rem*8, y1 : that.rem*7, x2 : that.rem*28, y2 : that.rem * 22}})},
            80:function() {that.demo.addText('They survive', [that.rem * 10, that.rem * 10], {
                fontWeight:900, fontSize: 85, time1:3000,
                narrowBorder:{x1:that.rem * 5.5, y1 : that.rem * 9, x2 : that.rem * 45, y2 : that.rem * 14.5}
            })},
            95:function() {that.demo.addMan({width:that.rem * 18, height:that.rem * 18, x:that.rem*15, y : that.rem * 3,
                alpha:0.8, time:2500, narrowBorder:{x1:that.rem*13, y1 : that.rem*2, x2 : that.rem*35, y2 : that.rem * 22}})},
            105:function() {that.demo.addText('But is not the cold that makes live here a living hell', [that.rem * 4.5, that.rem * 10], {
                fontWeight:900, fontSize: 50, time1:3000,
                narrowBorder:{x1:that.rem * 4, y1 : that.rem * 9, x2 : that.rem * 47, y2 : that.rem * 16}
            })},
            120:function() {that.demo.addEyes([that.rem * 18, that.rem * 10], {
                width:that.rem*16, height:that.rem*10, time1:3000, time2:1500,
                // narrowBorder:{x1:that.rem * 5.5, y1 : that.rem * 9, x2 : that.rem * 45, y2 : that.rem * 14.5}
            })},
            135:function() {that.demo.addText('It`s the true masters of this land', [that.rem * 5.5, that.rem * 10], {
                fontWeight:900, fontSize: 64, time1:3000,
                narrowBorder:{x1:that.rem * 5, y1 : that.rem * 9, x2 : that.rem * 45, y2 : that.rem * 18}
            })},
            150:function() {that.demo.addEyes([that.rem * 5, that.rem * 3], {
                width:that.rem*8, height:that.rem*5, time1:3000,
                // narrowBorder:{x1:that.rem * 5.5, y1 : that.rem * 9, x2 : that.rem * 45, y2 : that.rem * 14.5}
            })},
            152:function() {that.demo.addEyes([that.rem * 2, that.rem * 14], {
                width:that.rem*8, height:that.rem*5, time1:3000,
                // narrowBorder:{x1:that.rem * 5.5, y1 : that.rem * 9, x2 : that.rem * 45, y2 : that.rem * 14.5}
            })},
            154:function() {that.demo.addEyes([that.rem * 34, that.rem * 5], {
                width:that.rem*8, height:that.rem*5, time1:3000,
                // narrowBorder:{x1:that.rem * 5.5, y1 : that.rem * 9, x2 : that.rem * 45, y2 : that.rem * 14.5}
            })},
            156:function() {that.demo.addEyes([that.rem * 38, that.rem * 16], {
                width:that.rem*8, height:that.rem*5, time1:3000,
                // narrowBorder:{x1:that.rem * 5.5, y1 : that.rem * 9, x2 : that.rem * 45, y2 : that.rem * 14.5}
            })},
            162:function() {that.demo.addText('It`s the White Horde', [that.rem * 6, that.rem * 10], {
                fontWeight:900, fontSize: 65, time1:3000,
                //narrowBorder:{x1:that.rem * 5.5, y1 : that.rem * 9, x2 : that.rem * 45, y2 : that.rem * 14.5}
            })},
            178:function() {that.demo.addMan({width:that.rem * 18, height:that.rem * 18, x:that.rem*15, y : that.rem * 3,
                alpha:0.8, time:2500, noHiding:true})},
            // 4:function() {that.demo.borders(200, 200, 600, 400)},
            // 6:function() {that.demo.snow(500)},
            // 10:function() {that.demo.snow(50)},
        };
        this.demoStep = 0;
        
        this.init = function() {

            this.isLocalDevelopment = $('#isLocalDevelopment').length > 0;

            var that = this;
            var imagesToLoad = [];
            for (var i in this.images) {
                imagesToLoad.push(this.images[i]);
            }
            PIXI.loader
                .add(imagesToLoad)
                .load(function() {
                    that.runDemo();
                    that.state = that.game;
                    setTimeout(function () {

                        that.gameLoop();
                    }, 300);
                });



            this.renderer = PIXI.autoDetectRenderer(this.screenWidth, this.screenHeight);
            this.renderer.backgroundColor = 0xffffff;
            // this.renderer.backgroundColor = 0xdddddd;
            $('.demo-screen').append(this.renderer.view);
            // document.body.appendChild(this.renderer.view);
            this.stage = new PIXI.Container();
            this.layer1 = new PIXI.Container();
            this.layer2 = new PIXI.Container();
            this.stage.addChild(this.layer1);
            this.stage.addChild(this.layer2);
            this.stage.addChild(this.animations.particlesContainer);
            this.renderer.render(this.stage);
            // this.animations.initSVG();
            var maxGameSize = 546;
            var width = $(window).width();
            if (width < maxGameSize) {
                // oh this is a mobile... we are fucked
                maxGameSize = width;
                this.isMobile = true;
                // this.map.cellSize = Math.floor((maxGameSize / 16) - 2);
                // this.monsterSize = this.towerSize = Math.floor(this.map.cellSize / 4 * 3);
            }
            // this.interface.init();
        };

        this.runDemo = function () {
            // info('Demo step = '+ this.demoStep);
            // var texture = PIXI.Texture.fromImage(PIXI.loader.resources[this.images.man]);
            // var man0Sprite = new PIXI.Sprite(texture);

            if (this.demoConfig[this.demoStep] !== undefined) {
                
                this.demoConfig[this.demoStep]();
            }


            // this.renderer.render(this.stage);
            var that = this;
            this.demoStep++;
            setTimeout(function () {

                that.runDemo();
            }, 500);
        };

        

        this.gameLoop = function(){

            var that = this;
            //Loop this function 60 times per second
            requestAnimationFrame(function() {
                that.gameLoop()
            });

            //Update the current game state:
            this.state();

            //Render the stage
            this.animations.run();
            this.renderer.render(this.stage);
        };
        
        this.game = function() {

        };
        
        this.createSnowCanon = function() {
                
        };
        
        
        
        


        this.startGame = function(time) {
            this.gameStarted = true;

        };
        
    };
    var game = new WhiteHorde.Game();
    game.init();
    // game.action();
});