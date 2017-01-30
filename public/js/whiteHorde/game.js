


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
        this.interface = new WhiteHorde.Interface(this);
        this.characterHelper = new WhiteHorde.CharacterHelper(this);
        this.settlement = new WhiteHorde.Settlement(this);
        this.inventory = new WhiteHorde.Inventory(this);
        // this.interface = new Houston.Interface(this);
        // this.monsters = {};
        // this.nextMoves = {};
        // this.towers = {};
        // this.money = 50;
        this.stage = {};
        this.whiteHordeData = {};
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
        
        this.init = function() {

            this.isLocalDevelopment = $('#isLocalDevelopment').length > 0;

            if ($('body.WhiteHorde.demo').length) {
                this.demo.init();
            } else {
                this.whiteHordeData = whiteHordeData;
                this.settlement.init();
                this.inventory.init();
                this.interface.init();
            }
            
            // this.interface.init();
        };
        
        this.action = function(actionName, data) {
            var dataString = JSON.stringify(data);
            var that = this;
            Ajax.json('/WhiteHorde/action', {
                data: 'action=' + actionName + '&data=' + dataString,
                callBack : function(data){ that.callback(data) }
            });    
        };
        this.callback = function (data) {
            info (data);
            if (data.resources !== undefined) {
                this.settlement.updateResources(data.resources);
            }
            if (data.game !== undefined) {
                if (data.game.error !== undefined) {
                    this.interface.vue.openDialog('alert-ref');
                }
                if (data.game.message !== undefined) {
                    this.interface.vue.alert.content = data.game.message;
                    this.interface.vue.openDialog('alert-ref');
                }

                if (data.game.actions !== undefined && data.game.actions.length > 0) {
                    for (var i in data.game.actions) {
                        var actionName = data.game.actions[i].action;
                        var arguments = data.game.actions[i].arguments;
                        executeFunctionByName(actionName, this, [arguments]);
                    }
                }
            }
        };

        this.runDemo = function () {
            // info('Demo step = '+ this.demoStep);
            // var texture = PIXI.Texture.fromImage(PIXI.loader.resources[this.images.man]);
            // var man0Sprite = new PIXI.Sprite(texture);

            if (this.demo.demoConfig[this.demo.demoStep] !== undefined) {
                
                this.demo.demoConfig[this.demo.demoStep]();
            }


            // this.renderer.render(this.stage);
            var that = this;
            this.demo.demoStep++;
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