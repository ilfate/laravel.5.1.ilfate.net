


$(document).ready(function() {
    if (!$('body.WhiteHorde').length) {
        return;
    }

    WhiteHorde.Animations = function(game) {
        this.game = game;
        this.svg = $('<div></div>');

        this.particles = [];
        // this.particlesContainer = new PIXI.Container();
        this.particlesContainer = new PIXI.ParticleContainer(5000, {alpha: true});
        this.wind = [-15, 8];
        this.windSpeed = [-1, 1];
        this.newSprites = [];

        this.border = {
            x1 : 0,
            y1 : 0,
            x2 : this.game.screenWidth,
            y2 : this.game.screenHeight,
        };
        this.lastParticleOptions = {};
        this.animations = [];
        
        this.animate = function(sprite, properties, duration, callback) {
            var time = (new Date()).getTime();
            var current = {};
            for (var key in properties) {
                current[key] = sprite[key];
            }
            var animation = {
                sprite:sprite,
                properties:properties,
                current:current,
                duration:duration,
                end:duration + time,
                start:time,
                callback:callback,
            };
            this.animations.push(animation);
        };

        this.run = function() {
            if (this.newSprites) {
                for(var i in this.newSprites) {
                    var newSprite = this.newSprites.shift();
                    // this.particlesContainer.addChild(newSprite);
                    this.particlesContainer.addChild(newSprite);
                }
            }
            if (this.animations.length > 0) {
                var time = (new Date()).getTime();
                for (var i in this.animations) {
                    var animation = this.animations[i];
                    if (animation.end < time) {
                        this.setSpriteProperties(animation.sprite, animation.properties);
                        if (animation.callback) { animation.callback(); }
                        this.animations.splice(i, 1);
                    } else {
                        this.setPartialSpriteProperties(animation.sprite, animation.current, animation.properties,
                            1 - ((animation.end - time) / animation.duration)
                        );
                    }
                }
            }
            for(var i in this.particles) {
                var particle = this.particles[i];
                if (!particle.visible) continue;
                particle.sprite.x += particle.vx;
                particle.sprite.y += particle.vy;
                if (particle.maxAlpha !== undefined && particle.maxAlpha > particle.sprite.alpha) {
                     // info(particle.sprite.alpha);
                    particle.sprite.alpha = 1;
                    particle.sprite.visible = 1;
                }
                if (
                    particle.sprite.x + particle.width > this.border.x2
                    || particle.sprite.y + particle.width > this.border.y2
                    || particle.sprite.x + particle.width < this.border.x1
                    || particle.sprite.y + particle.width < this.border.y1
                ) {
                    if (particle.restart !== undefined) {
                        switch (true) {
                            case (particle.sprite.x + particle.width > this.border.x2): particle.sprite.x = this.border.x1; break;
                            case (particle.sprite.y + particle.width > this.border.y2): particle.sprite.y = this.border.y1; break;
                            case (particle.sprite.x < this.border.x1): particle.sprite.x = this.border.x2 - particle.width; break;
                            case (particle.sprite.y < this.border.y1): particle.sprite.y = this.border.y2 - particle.width; break;
                        }
                    } else {
                        particle.sprite.visible = false;
                        this.particlesContainer.removeChild(particle.sprite);
                        this.particles.splice(i, 1);
                        // info(particle.sprite.x);
                        continue;
                    }
                }
                if (particle.wind !== undefined && rand(0, 3) === 3) {
                    var low = (particle.reverceSizePercent / 2) + 0.5;
                     particle.vx = (this.wind[0] + rand (-3, 3)) * low;
                     particle.vy = (this.wind[1] + rand (-2, 2)) * low;
                }
            }
            if (rand(0,7) == 7) {
                this.updateWind();
            }
        };

        this.updateWind = function() {
            var newWindX = this.wind[0] + this.windSpeed[0];
            if (newWindX > 0) newWindX = 0;
            else if (newWindX < -30) newWindX = -30;
            var newWindY = this.wind[1] + this.windSpeed[1];
            if (newWindY < 5) newWindY = 5;
            else if (newWindY > 20) newWindY = 20;
            this.wind = [newWindX, newWindY];

            if (rand(0, 5) == 5) {
                var newWindSpeedX = rand(-1, 1);
                var newWindSpeedY = rand(-1, 1);
                this.windSpeed = [newWindSpeedX, newWindSpeedY];
            }
        };

        this.createParticle = function(options) {
            var circle = new PIXI.Graphics();
            if (options.color === undefined) { options.color = 0x9966FF}
            if (options.x1 === undefined) { options.x1 = this.border.x1}
            if (options.y1 === undefined) { options.y1 = this.border.y1}
            if (options.r1 === undefined) { options.r1 = 50}
            if (options.vx === undefined) { options.vx = 1}
            if (options.vy === undefined) { options.vy = 1}
            if (options.xs1 !== undefined && options.xs2 !== undefined) { options.x1 = rand(options.xs1, options.xs2)}
            if (options.ys1 !== undefined && options.ys2 !== undefined) { options.y1 = rand(options.ys1, options.ys2)}
            if (options.size === undefined) { options.size = 2}
            if (options.size1 !== undefined && options.size2 !== undefined) {
                options.size = rand(options.size1, options.size2);
                if (options.reverseSuperRand !== undefined) {
                    options.size = rand(options.size1, options.size);
                }
                circle.reverceSizePercent = ((options.size - options.size1) / (options.size2 - options.size1));
            }
            if (options.wind !== undefined) { circle.wind = true; }
            if (options.restart !== undefined) { circle.restart = true; }
            if (options.sizeAlpha !== undefined) {
                var addAlpha = (1 - options.sizeAlpha) * (1 - circle.reverceSizePercent);
                circle.alpha = options.sizeAlpha + addAlpha;
            }
            if (options.addSlowWithAlpha !== undefined) {
                circle.maxAlpha = circle.alpha;
                circle.alpha = 0;
            }

            
            circle.beginFill(options.color);
            var x = options.x1 + (options.r1 * Math.random()) - (options.r1 / 2);
            var y = options.y1 + (options.r1 * Math.random()) - (options.r1 / 2);
            circle.drawCircle(
                0,
                0,
                options.size);
            circle.endFill();
            circle.x = x;
            circle.y = y;
            circle.vx = options.vx;
            circle.vy = options.vy;
            var sprite = new PIXI.Sprite(this.game.renderer.generateTexture( circle));
            circle.sprite = sprite;
            sprite.x = circle.x;
            sprite.y = circle.y;
            sprite.alpha = circle.alpha;
            this.particles.push(circle);
            this.newSprites.push(sprite);
            this.lastParticleOptions = options;
        };

        this.changeParticlesNumber = function(newNumber) {
            if (this.particles.length < newNumber) {
                // this.lastParticleOptions.addSlowWithAlpha = true;
                for (var i = 0; i < newNumber - this.particles.length; i++) {
                    this.createParticle(this.lastParticleOptions);
                }
            } else if (this.particles.length > newNumber) {
                var numToDelete = this.particles.length - newNumber;
                for(var i in this.particles) {
                    this.particles[i].restart = undefined;
                    numToDelete--;
                    if (numToDelete <= 0) break;
                }
            }
        };
        
        this.resetBorder = function(duration) {
            this.animate(this.border, {
                x1 : 0,
                y1 : 0,
                x2 : this.game.screenWidth,
                y2 : this.game.screenHeight,
            }, duration);    
        };

        this.setSpriteProperties = function (sprite, properties) {
            for (var key in properties) {
                sprite[key] = properties[key];
            }
        };

        this.setPartialSpriteProperties = function (sprite, was, will, percent) {
            for (var key in will) {
                sprite[key] = was[key] + (will[key] - was[key]) * percent;
            }
        };
    };

    WhiteHorde.Interface = function(game) {
        this.game = game;

        this.vue = {};
        this.chatVue = {};
        this.messages = [{text:'fffrfrfrf'}, {text:'awdasdasd'}];

        this.init = function() {

            var that = this;
            info(this.game.whiteHordeData);

            Vue.component('inventory', {
                props: ['items'],
                template: '#template-inventory',
            });
            Vue.component('item-slot', {
                props: ['character', 'type'],
                template: '#template-item-slot',
                methods: {
                    allowDrop: function (event) { event.preventDefault(); },
                    addItem : function(ev, character) {
                        ev.preventDefault();
                        var type = ev.dataTransfer.getData("text");
                        var item = false;
                        for (var i in that.game.settlement.items) {
                            if (that.game.settlement.items[i].name == type) {
                                item = that.game.settlement.items[i];
                                break;
                            }
                        }
                        if (!item) return;
                        item.q -= 1;
                        character.inventory[item.location] = item;
                    }
                },
            });
            Vue.component('item', {
                props: {'item': {}, 'showQuantity': {default:true}},
                template: '#template-item',
                // data: function() { return {showQuantity: true}; },
                methods: {
                    drag : function(event, i) { event.dataTransfer.setData("text", i.name); },
                },
            });
            Vue.component('character-info', {
                props: ['character'],
                template: '#template-character-info',

            });
            Vue.component('characters-container', {
                props: ['characters'],
                template: '#template-characters-container'
            });

            this.chatVue = new Vue({
                el: '#chat-app',
                data: {
                    messages: this.messages
                }
            });
            this.vue = new Vue({
                el: '#game-app',
                data: {
                    buildings:this.game.settlement.buildings,
                    settlementItems:this.game.settlement.items,
                    settlementResources:this.game.settlement.resources,
                    settlementCharacters:this.game.settlement.characters,
                    characterInfo: {},
                    showSettlementInventory : false,
                    showCharacterInfo : false,
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
                    buildingClick : function (building) {
                        that.buildingClick(building);
                    }
                }
            });


            // $('.pause-button').on('click', function () {
            //     that.pauseToggle();
            // });

        };
        this.buildingClick = function (building) {
            switch (building.type) {
                case 'warhouse':
                    this.game.inventory.showInventory();
                    this.vue.showSettlementInventory = true;
                    break;
            }   
        };

        
    };
});