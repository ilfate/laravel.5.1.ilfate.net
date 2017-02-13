
$(document).ready(function() {
    if (!$('body.shipAi').length) {
        return;
    }
    ShipAi.HexApp = function(game) {
        this.vue = {};
        this.game = game;
        this.init = function() {
            
            var that = this;

            this.vue = new Vue({
                el: '#hex-app',
                data: {

                    alert: {content:"empty",ok:'ok'}
                },
                created: function () {

                    this.updateStarsLocation();
                    var thisvue = this;
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
                    
                    updateStarsLocation: function(id) {
                        var radius = 8000;
                        var radiusPixels = 345;
                        var hexWidthInLightYears = that.getHexWidth(radius);
                        var hexWidthInPixels = that.getHexWidth(radiusPixels);
                        var yearsPerPixel = hexWidthInLightYears / hexWidthInPixels;
                        $('.star-container').each(function (num, el) {
                            el = $(el);
                            el.css({
                                'margin-left': (parseInt(el.data('x')) / yearsPerPixel) + 'px',
                                'margin-top': (parseInt(el.data('y')) / yearsPerPixel) + 'px',
                            })
                        });
                    },
                    starClick: function (id) {
                        window.location = '/shipAi/star/' + id;
                    }
                }
            });
            
           
        };

        this.getHexWidth = function(radius) {
            return Math.round(radius * Math.sqrt(3), 2);
        }
    };
});