
$(document).ready(function() {
    if (!$('body.shipAi').length) {
        return;
    }
    ShipAi.StarApp = function(game) {
        this.vue = {};
        this.game = game;
        this.init = function() {
            
            var that = this;

            this.vue = new Vue({
                el: '#star-app',
                data: {

                    alert: {content:"empty",ok:'ok'}
                },
                created: function () {

                    // this.updateStarsLocation();
                    // var thisvue = this;
                    // $(window).resize(function () { thisvue.updateStarsLocation(); });
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
                    starClick: function (id) {
                        window.location = '/shipAi/star/' + id;
                    }
                }
            });
        };
    };
});