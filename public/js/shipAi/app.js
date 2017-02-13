
$(document).ready(function() {
    if (!$('body.shipAi').length) {
        return;
    }
    ShipAi.Game = function() {
        this.rem = 20;
        this.screenWidth = 980;
        this.screenHeight = 600;
        this.interface = {};
        this.hexRadius = 1;
        this.rem = 20;
        
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


        this.init = function() {

            this.isLocalDevelopment = $('#isLocalDevelopment').length > 0;
            Vue.use(VueMaterial);
            Vue.material.registerTheme('default', {
                primary: 'blue',
                accent: 'red',
                warn: 'red',
                background: 'black'
            });

            if ($('body.shipAi.galaxy').length) {
                this.interface = new ShipAi.GalaxyApp(this);
                this.interface.init();
            }
                
            if ($('body.shipAi.hex').length) {
                this.interface = new ShipAi.HexApp(this);
                this.interface.init();
            }
                
            if ($('body.shipAi.star').length) {
                this.interface = new ShipAi.StarApp(this);
                this.interface.init();
            }
            
           
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

        
        
    };
    var game = new ShipAi.Game();
    game.init();
});