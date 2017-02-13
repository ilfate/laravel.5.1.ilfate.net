
$(document).ready(function() {
    if (!$('body.shipAi').length) {
        return;
    }
    ShipAi.GalaxyApp = function(game) {
        this.vue = {};
        this.game = game;
        this.init = function() {
            

            this.vue = new Vue({
                el: '#galaxy-app',
                data: {

                    alert: {content:"empty",ok:'ok'}
                },
                // watch : {
                //     buildings: {
                //         handler : function (val, oldVal) {
                //             console.log('new: %s, old: %s', val, oldVal)
                //         },
                //         deep: true
                //     },
                // },
                methods: {
                    openHex: function(id) {
                        window.location = '/shipAi/hex/' + id;
                    }
                }
            });
            
           
        };
    };
});