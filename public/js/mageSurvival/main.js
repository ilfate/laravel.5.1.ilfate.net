/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */

function MageS () {

}
MageS = new MageS();

$(document).ready(function() {
    if ($('body.mage-survival').length) {
        MageS.Game = new MageS.Game();
        var animations = new MageS.Animations(MageS.Game);
        var attacks = new MageS.Attacks(MageS.Game);
        var inventory = new MageS.Inventory(MageS.Game);
        var spellbook = new MageS.Spellbook(MageS.Game);
        var spells = new MageS.Spells(MageS.Game);
        var worlds = new MageS.Worlds(MageS.Game);
        var objects = new MageS.Objects(MageS.Game);
        var units = new MageS.Units(MageS.Game);
        var chat = new MageS.Chat(MageS.Game);
        var home = new MageS.Home(MageS.Game);
        var spellcraft = new MageS.Spellcraft(MageS.Game);
        var monimations = new MageS.Monimations(MageS.Game);
        MageS.Game.init(inventory, spellbook, spells, worlds, objects, units, chat, home, spellcraft, animations, attacks, monimations);
    }
});


MageS.Game = function () {
    this.color = {
        'blue': '#428BCA',
        'green': '#069E2D',
        'yellow': '#FFD416',
        'red': '#F21616',
        'orange': '#EF8354',
        'black': '#584D3D',
        'white': '#FFFFFF'
    };
    this.device = 'pc';
    this.inventory = {};
    this.spellbook = {};
    this.spells = {};
    this.worlds = {};
    this.objects = {};
    this.units = {};
    this.chat = {};
    this.home = {};
    this.spellcraft = {};
    this.animations = {};
    this.attacks = {};
    this.monimations = {};
    this.gameStatus = $('#game-status').val();
    this.rawData = [];
    this.svg = {};
    this.turn = 0;
    this.worldType = 0;
    this.actionInProcess = false;
    this.gameInited = false;
    /* CONFIG */
    this.fieldRadius = 5;
    this.cellSize = 1.6;
    this.itemSize = 1.7;
    this.rem = 20;
    this.mageInventorySize = 7 * this.itemSize + 0.9;
    this.mageMobileInventorySize = 6 * this.itemSize;
    this.animationTime = 350;
    this.battleFieldSize = (this.fieldRadius * 2 + 1) * this.cellSize;

    this.deviceCheck = function() {
        if ($(window).width() < 992) {
            this.device = 'tablet';
            if ($(window).width() < 768) {
                this.device = 'mobile';
            }
        } else {
            this.device = 'pc';
        }
    };

    this.init = function (inventory, spellbook, spells, worlds, objects, units, chat, home, spellcraft, animations, attacks, monimations) {
        this.inventory = inventory;
        this.spellbook = spellbook;
        this.spells = spells;
        spells.init();
        this.worlds = worlds;
        this.objects = objects;
        this.units = units;
        this.chat = chat;
        this.home = home;
        this.spellcraft = spellcraft;
        this.animations = animations;
        this.attacks = attacks;
        attacks.init();
        this.monimations = monimations;

        this.deviceCheck();

        switch (this.gameStatus) {
            case 'mage-list':
                this.home.init();
                this.initSVG(function() {
                    MageS.Game.replaceMissingSvg();
                });

                break;
            case 'mage-home':
                this.initSVG(function() {
                    // Get the SVG tag, ignore the rest
                    //MageS.Game.spellbook.buildSpells();
                    //MageS.Game.inventory.buildItems();
                    MageS.Game.replaceMissingSvg();
                    //MageS.Game.updateHealth(MageS.Game.rawData.mage);
                    MageS.Game.home.startAnimation();

                    setTimeout(function () {
                        MageS.Game.hideGameLoadOverlay();
                    }, 150);
                });
                break;
            case 'battle':
                this.deviceInit();
                info( this.device);

                this.initSVG(function() {
                    // Get the SVG tag, ignore the rest

                    //MageS.Game.pageResize();
                    MageS.Game.chat.buildChat();
                    MageS.Game.buildMap();
                    MageS.Game.drawMage(MageS.Game.rawData.mage);
                    MageS.Game.updateActions(MageS.Game.rawData.actions, true);
                    MageS.Game.spellbook.buildSpells();
                    MageS.Game.inventory.buildItems();
                    MageS.Game.buildUnits();
                    MageS.Game.replaceMissingSvg();
                    info(MageS.Game.rawData.mage);
                    MageS.Game.updateHealth(MageS.Game.rawData.mage);


                    if (MageS.Game.device !== 'mobile') {
                        setTimeout(function () {
                            MageS.Game.hideGameLoadOverlay();
                        }, 150);
                    } else {
                        $('#overlay-loading-text').hide();
                        var textEl = $('#overlay-tap-text');
                        textEl.show();
                        MageS.Game.monimations.blastInScale(textEl, 2);
                        $('.game-load-overlay').on('click', function(){
                            MageS.Game.hideGameLoadOverlay();
                        })

                    }

                });

                this.configureKeys();

                $('.inventory-shadow').on('click', function() {
                    MageS.Game.spellcraft.cancelCrafting();
                });


                break;
        }
    };
    this.hideGameLoadOverlay = function() {
        if (MageS.Game.gameInited) { return; }
        if (this.device == 'mobile') {
            //document.addEventListener("touchmove", function(e) { e.preventDefault() });


            //var body = document.documentElement;
            //if (body.requestFullscreen) { info('MOBIL FULLSCREEN 1'); body.requestFullscreen(); }
            //else if (body.webkitrequestFullscreen) { info('MOBIL FULLSCREEN 2'); body.webkitrequestFullscreen(); }
            //else if (body.mozrequestFullscreen) { info('MOBIL FULLSCREEN 3'); body.mozrequestFullscreen(); }
            //else if (body.msrequestFullscreen) { info('MOBIL FULLSCREEN 4'); body.msrequestFullscreen(); }
            MageS.Game.toggleFullScreen();
        }
        $('.game-load-overlay').animate({'opacity': '0'}, {
            duration: 1000,
            complete: function () {
                $('.loading-field').append(
                    $(this).find('.load-animation').css({
                        'width': '140%',
                        'height': '100%',
                        'margin-top': '5%'
                    })
                );
                $(this).remove();
            }
        });
        MageS.Game.gameInited = true;
    };
    this.deviceInit = function () {
        if (this.device == 'pc') {
            $('.interface-switch-panel').remove();
        } else {
            $('.toggle-inventory').on('click', function() {
                MageS.Game.inventory.toggleInventory();
            });
            $('.toggle-spellbook').on('click', function() {
                MageS.Game.spellbook.toggleSpellbook();
            });
            $('.toggle-chat').on('click', function() {
                MageS.Game.chat.swipeEnd();
            });

            //$('.toggle-mage-info').on('click', function() {
            //    MageS.Game.toggleMageInfo();
            //});
            if (this.device !== 'pc') {
                this.spellbook.showSpellbook();

                var hammertime = new Hammer(document.getElementById('battle-border'), {});
                hammertime.get('pan').set({ direction: Hammer.DIRECTION_ALL });
                hammertime.get('swipe').set({ direction: Hammer.DIRECTION_VERTICAL });

                hammertime.on('panup',    function(ev) { MageS.Game.swipe(ev,0); });
                hammertime.on('panright', function(ev) { MageS.Game.swipe(ev,1); });
                hammertime.on('pandown',  function(ev) { MageS.Game.swipe(ev,2); });
                hammertime.on('panleft',  function(ev) { MageS.Game.swipe(ev,3); });
                hammertime.on('panend',  function(ev) { MageS.Game.swipeEnd(ev); });

                $('#mobile-spell-info-container').on('click', function() {
                    MageS.Game.spellbook.toggleHiddenDescription();
                });

                var hammertime = new Hammer(document.getElementById('mobile-spell-info-container'), {});
                hammertime.get('pan').set({ direction: Hammer.DIRECTION_ALL });
                hammertime.on('panright', function(ev) { MageS.Game.spellbook.panMobileSpellDescriptionRight(ev); });
                hammertime.on('panleft', function(ev) { MageS.Game.spellbook.panMobileSpellDescriptionLeft(ev); });
                hammertime.on('panend',  function(ev) { MageS.Game.spellbook.toggleHiddenDescription(); });


            }
        }
    };
    this.toggleFullScreen = function () {
        var doc = window.document;
        var docEl = doc.documentElement;

        var requestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
        var cancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;
        if (!requestFullScreen) {
            return;
        }
        if(!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {
            requestFullScreen.call(docEl);
        }
        else {
            cancelFullScreen.call(doc);
        }
    };
    this.trytoGoFullScreen = function() {
        var doc = window.document;
        if(!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {
            this.toggleFullScreen();
        }
    };

    this.pageResize = function() {
        //$(window).bind('orientationchange resize', function(event){
        //    if (event.orientation) {
        //        if (event.orientation == 'landscape') {
        //            if (window.rotation == 90) {
        //                windowRotate(this, -90);
        //            } else {
        //                windowRotate(this, 90);
        //            }
        //        }
        //    }
        //});
        var rem = this.rem;
        var width = $(window).width();
        switch (this.device) {
            case 'mobile':
                 if (width >= 428) { rem = 24; }
                else if (width >= 408) { rem = 23; }
                else if (width >= 388) { rem = 22; }
                else if (width >= 368) { rem = 21; }
                else if (width <= 300) { rem = 17; }
                else if (width <= 320) { rem = 18; }
                else if (width <= 338) { rem = 19; }
                else if (width <= 360) { rem = 20; }
                break;
        }
        //info('rem=' + rem);
        this.rem = rem;
        $('html').css('font-size', rem + 'px');
    };

    this.swipe = function (event, d) {
        //if ($('#move-control-field').hasClass('disable')) { return false; }
        //if (event.distance < 25) { return false; }
        //switch (d) {
        //    case 0: MageS.Game.action('move-up'); break;
        //    case 1: MageS.Game.action('move-right'); break;
        //    case 2: MageS.Game.action('move-down'); break;
        //    case 3: MageS.Game.action('move-left'); break;
        //}
    };
    this.swipeEnd = function (event) {
        if ($('#move-control-field').hasClass('disable')) { return false; }
        if (event.distance < 20) { return false; }
        switch (event.additionalEvent) {
            case 'panup': MageS.Game.action('move-up'); break;
            case 'panright': MageS.Game.action('move-right'); break;
            case 'pandown': MageS.Game.action('move-down'); break;
            case 'panleft': MageS.Game.action('move-left'); break;
        }
    };

    this.buildMap = function() {
        this.rawData = mageSurvivalData;
        this.turn = this.rawData.turn;
        this.worldType = this.rawData.world;
        for(var y in this.rawData.map) {
            for(var x in this.rawData.map[y]) {
                this.drawCell(this.rawData.map[y][x], x, y);
            }
        }
        for(var y in this.rawData.objects) {
            for(var x in this.rawData.objects[y]) {
                this.drawObject(this.rawData.objects[y][x], x, y);
            }
        }
    };

    this.action = function(action, data) {
        if (this.actionInProcess) {
            info('Action is locked');
            return;
        }
        this.startAction(action);
        this.spellbook.turnOffPatterns();
        this.spellbook.removePermanentTooltip();
        $('.spellBook .spell.active').removeClass('active');
        var actionName = '';
        var dataString = '';
        switch (action) {
            case 'move-up':
                actionName = 'move';
                dataString = '{"d":"0"}';
                break;
            case 'move-right':
                actionName = 'move';
                dataString = '{"d":"1"}';
                break;
            case 'move-down':
                actionName = 'move';
                dataString = '{"d":"2"}';
                break;
            case 'move-left':
                actionName = 'move';
                dataString = '{"d":"3"}';
                break;
            case 'objectInteract':
                actionName = 'objectInteract';
                dataString = data;
                break;
            case 'craftSpell':
                actionName = 'craftSpell';
                dataString = data;
                break;
            case 'spell':
                actionName = 'spell';
                dataString = data;
                break;
            default:
                info('action not found');
                return;
        }
        Ajax.json('/Spellcraft/action', {
            data: 'action=' + actionName + '&data=' + dataString,
            callBack : function(data){ MageS.Game.callback(data) }
        });
        if (this.device == 'mobile') {
            MageS.Game.trytoGoFullScreen();
        }
    };

    this.callback = function(data) {
        if (data.action) {
            switch (data.action) {
                case 'mage-create':
                    window.location = '/Spellcraft';
                    break;
                case 'move':
                    //this.moveAnimate(data);
                    break;
                case 'rotate':
                    //this.rotateAnimate(data);
                    break;
                case 'objectInteract':
                    this.inventory.addItems(data.game);
                    break;
                case 'craftSpell':
                    this.spellcraft.spellCrafted(data);
                    break;
                case 'spell':
                    //this.spells.castSpell(data);
                    break;
                case 'error-message':
                    info(data.game.messages);
                    this.endAction();
                    if (this.spells.spellAnimationRunning) {
                        this.spells.stopAnimation = true;
                    }
                    break;
            }
        }
        info(data);
        if (data.game.actions) {
            this.updateActions(data.game.actions, false);
        }
        if (data.game.items) {
            this.inventory.updateItems(data.game.items);
        }
        if (data.game.spells) {
            this.spellbook.updateSpells(data.game.spells);
        }
        if (data.game.messages) {
            this.chat.postMessages(data.game.messages);
        }
        if (data.game.events) {
            this.animations.animateEvents(data.game);
        }
        if (data.game.turn) {
            this.turn = data.game.turn;
            this.spellbook.turn();
        }

    };

    this.updateActions = function (actions, isFirstLoad) {
        actions.push({'name':'Craft Spell', 'method':'craft-spell', 'key':'Q' ,'noAjax':true, 'location':'actions', 'icon':'icon-fizzing-flask'});
        actions.push({'name':'Test Spell', 'method':'test-spell', 'key':'T' ,'noAjax':true, 'location':'actions', 'icon':'icon-fizzing-flask'});
        var actionsEl = $('.actions');
        var existingActions = {};
        actionsEl.find('.action').each(function() {
            existingActions[$(this).data('method')] = $(this);
        });
        for (var i in actions) {
            var method = actions[i].method;
            if (existingActions[method] !== undefined) {
                existingActions[method] = false;
                continue;
            }

            switch (actions[i].location) {
                case 'actions':
                    var temaplate = $('#template-action-button').html();
                    Mustache.parse(temaplate);
                    var key = '';
                    if (actions[i].key !== undefined) {
                        key = actions[i].key;
                    }
                    var rendered = Mustache.render(temaplate, {'name': actions[i].name, 'method':method, 'key': key});
                    var obj = $(rendered);
                    var icon = $(this.svg).find('#' + actions[i].icon + ' path');
                    obj.find('svg').append(icon.clone());
                    actionsEl.append(obj);
                    if (!isFirstLoad) {
                        obj.find('a')
                            .css({'opacity': 0.3})
                            .animate({'opacity': 1}, {
                                queue: false,
                                duration: this.animationTime / 2
                            });
                    }
                    if (actions[i].noAjax == undefined) {
                        obj.on('click', function () {
                            MageS.Game.action('objectInteract', '{"method":"' + $(this).data('method') + '"}')
                        });
                    }
                    break;
                case 'move-0':
                case 'move-1':
                case 'move-2':
                case 'move-3':
                    var location = $('#move-control-field .' + actions[i].location);
                    if (location.data('method') == actions[i].method) {
                        continue;
                    }
                    location.data('method', actions[i].method);
                    if (location.hasClass('svg-replace')) {
                        // page is not inited yet
                        location.data('svg', actions[i].icon);
                    } else {
                        location.find('svg path').remove();
                        var icon = $(this.svg).find('#' + actions[i].icon + ' path');
                        location.find('svg').append(icon.clone());
                    }
                    break;
                default: info('No location "' + actions[i].location + '" found for action');
            }


        }
        for (var i in existingActions) {
            if (existingActions[i]) {
                existingActions[i].remove();
            }
        }
        if (isFirstLoad) {
            $('.method-craft-spell').on('click', function () {
                MageS.Game.spellcraft.showSpellCrafting();
            });
            $('.method-test-spell').on('click', function () {
                MageS.Game.spells.isSecondPartWaiting = true;
                // MageS.Game.spells.currentSpellData = {'data': [
                //     [-2, -3],[-1, -3],[0, -3],[1, -3],[2, -3],
                //     [-2, -2],[-1, -2],[0, -2],[1, -2],[2, -2]
                // ]};
                // MageS.Game.spells.currentSpellData = {'d': $('.battle-border .mage').data('d')};
                MageS.Game.spells.currentSpellData = {'targetX': 0, 'targetY': -1, 'd':0};
                //     {'point':[-1,0], 'targets':[[-1, -2], [0, 2]]},
                //     {'point':[-2,0], 'targets':[[-1, -2], [0, 2]]},
                //     {'point':[-3,0], 'targets':[[-1, -2], [0, 2]]},
                //     {'point':[-4,0], 'targets':[[-1, -2], [0, 2]]},
                // ]};
                //MageS.Game.spells.startCast('Fireball');
                //MageS.Game.spells.startCast('IceCrown');
                // MageS.Game.spells.startCast('ButthurtJump');
                MageS.Game.spells.startCast('Blizzard');
                // MageS.Game.objects.activate({'action': 'bombTrigger', 'targetX':-3,'targetY':-2})
            });
            $('#move-control-field .control-arrow').on('click', function () {
                switch ($(this).data('d')) {
                    case 0:
                        MageS.Game.action('move-up');
                        break;
                    case 1:
                        MageS.Game.action('move-right');
                        break;
                    case 2:
                        MageS.Game.action('move-down');
                        break;
                    case 3:
                        MageS.Game.action('move-left');
                        break;
                }
            });
        } else {
            // we need to show action again

        }
    };

    this.keyPressed = function(key) {
        var action = $('.actions .action.key-' + key);
        if (action.length) {
            action.click();
        }
    };

    this.buildUnits = function() {
        for(var y in this.rawData.units) {
            for(var x in this.rawData.units[y]) {
                this.units.drawUnit(this.rawData.units[y][x], x, y);
            }
        }
    };

    this.updateHealth = function(mage) {
        var total = mage.maxHealth;
        var health = Math.round(mage.health / total * 100);
        if (this.device == 'mobile') {
            var currentHealth = parseInt($('.health-mobile-info .health .value').html());
            if (currentHealth != mage.health || !this.gameInited) {
                if (currentHealth < mage.health) {
                    MageS.Game.monimations.bounce($('.health-mobile-info .health'));
                    $('.health-mobile-info .health .svg').removeClass('color-red');
                    $('.health-mobile-info .health .normal path')
                        .animate({'svgFill':'#069E2D'})
                        .animate({'svgFill':'#FF8360'});
                } else if (currentHealth > mage.health) {
                    MageS.Game.monimations.skweeze($('.health-mobile-info .health'));
                }
                $('.health-mobile-info .health .cover').height(100 - health + '%');
                $('.health-mobile-info .health .value').html(mage.health);
            }
            var currentArmor = parseInt($('.health-mobile-info .armor .value').html());
            var color = '#FF8360';
            if (currentArmor != mage.armor) {
                if (currentArmor < mage.armor) {
                    color = '#069E2D';
                }
                $('.health-mobile-info .armor .svg').removeClass('color-brown');
                $('.health-mobile-info .armor path')
                    .animate({'svgFill':color})
                    .animate({'svgFill':'#5E412F'});
                $('.health-mobile-info .armor .value').html(mage.armor);
            }
        } else {
            if (mage.armor !== undefined) {
                total += mage.armor;
            }
            var armor = 0;
            if (mage.armor !== undefined) {
                armor = Math.round(mage.armor / total * 100);
            }
            $('.health-bar .progress-bar-success').css('width', health + '%');
            $('.health-bar .progress-bar-warning').css('width', armor + '%');

            $('.health-bar .health-value').html(mage.health + 'HP');
            $('.health-bar .armor-value').html(armor);
        }
    };

    this.initSVG = function(callback) {
        var url = '/images/game/mage/game-icons.svg';
        jQuery.get(url, function(data) {

            MageS.Game.svg = jQuery(data).find('svg');
            info(MageS.Game.svg);
            callback();
        }, 'xml');
    };

    this.replaceMissingSvg = function() {
        $('.svg.svg-replace').each(function() {

            MageS.Game.replaceSvg($(this));
        });
    };

    this.replaceSvg = function(svgContainerEl) {
        var icon = MageS.Game.svg.find('#' + svgContainerEl.data('svg') + ' path');
        svgContainerEl.removeClass('svg-replace').find('svg').append(icon.clone());
    };

    this.startAction = function(action) {
        switch (action) {
            case 'spell':
                break;
            case '':
            default:
                //$('.battle-border').addClass('action');
                $('.loading-field').fadeIn();
                break;
        }
        $('.actions-container .actions').fadeOut(this.animationTime / 2);
        this.actionInProcess = true;
    };

    this.endAction = function() {
        this.actionInProcess = false;
        $('.loading-field').fadeOut(50);
        $('.actions-container .actions').fadeIn();
    };

    this.drawCell = function(cell, x, y, target) {
        if (!target) {
            target = $('.battle-field.current');
        }
        var temaplate = $('#template-map-cell').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'x': x, 'y': y, 'class': cell});
        var obj = $(rendered);
        this.worlds.cell(this.worldType, cell, obj);
        target.append(obj);
        obj.css({
            'margin-left' : (x * this.cellSize) + 'rem',
            'margin-top' : (y * this.cellSize) + 'rem'
        })
    };

    this.drawObject = function(object, x, y, target) {
        if (!target) {
            target = '.battle-field.current';
        }
        var temaplate = $('#template-object').html();
        Mustache.parse(temaplate);
        var addClass = '';
        if (object.viewData.class !== undefined) {
            addClass = object.viewData.class;
        }
        var rendered = Mustache.render(temaplate, {'id': object.id, 'type':object.type, 'addClass' : addClass});
        var obj = $(rendered);
        var icon = $(this.svg).find('#' + object.config.icon + ' path');
        obj.find('svg').append(icon.clone());
        if (object.config.iconColor !== undefined) {
            obj.find('.svg').addClass(object.config.iconColor);
        }
        $(target + ' .cell.x-' + x + '.y-' + y).append(obj);
        return obj;
    };
    
    this.drawMage = function(mageConf) {
        var temaplate = $('#template-mage').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'d': mageConf.d});
        var obj = $(rendered);
        $(this.svg).find('#icon-mage-1 path').each(function() {
            obj.find('svg').append($(this).clone());
        });
        obj.animateRotate(0, mageConf.d * 90, 10);

        $('.mage-container').prepend(obj);
        if (mageConf.flags) {
            this.addMageStatus(mageConf.flags);
        }
    };

    this.addMageStatus = function(flags) {
        this.units.addFlag($('.mage-container .mage'), flags);
    };
    
    this.itemsMessage = function(message, strong) {
        var temaplate = $('#template-alert-items').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'message': message, 'strong': strong});
        var obj = $(rendered);
        $('.inventory-message-container').append(obj);
        setTimeout(function(){
            $('.inventory-message-container .alert').hide(500);
        }, 3000);
    };

    this.configureKeys = function() {
        $(document).keypress(function (event) {
            switch (event.keyCode) {
                case 40: // up
                case 1094:
                    MageS.Game.action('move-up');
                    break;
                case 37: // left
                case 1092:
                    MageS.Game.action('move-left');
                    break;
                case 38: // down
                case 1099:
                    MageS.Game.action('move-down');
                    break;
                case 39: // right
                case 1074:
                    MageS.Game.action('move-right');
                    break;

                case 13:  // Enter
                    break;
                case 119 : // w
                    MageS.Game.action('move-up');
                    break;
                case 97 : // a
                    MageS.Game.action('move-left');
                    break;
                case 115 : // s
                    MageS.Game.action('move-down');
                    break;
                case 100 : // d
                    MageS.Game.action('move-right');
                    break;
                case 32 :  // space
                    break;
                case 113 :  // q
                    MageS.Game.spellcraft.showSpellCrafting();
                    break;
                case 101 :  // e
                    MageS.Game.keyPressed('E');
                    break;
                case 114 :  // r
                    break;
                case 102 :  // f
                    break;
                case 0 :                  //// For Mozila
                    switch (event.charCode) {
                        case 119 : // w
                            MageS.Game.action('move-up');
                            break;
                        case 97 : // a
                            MageS.Game.action('move-left');
                            break;
                        case 115 : // s
                            MageS.Game.action('move-down');
                            break;
                        case 100 : // d
                            MageS.Game.action('move-right');
                            break;
                        case 32 :  // space
                            break;
                        case 101 :  // e
                            MageS.Game.keyPressed('E');
                            break;
                        case 113 :  // q
                            MageS.Game.spellcraft.showSpellCrafting();
                            break;
                        case 114 :  // r
                            break;
                        case 102 :  // f
                            break;
                    }
                    break;
            }
        });
    }

};

