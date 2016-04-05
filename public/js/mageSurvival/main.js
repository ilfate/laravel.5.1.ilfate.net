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
        var inventory = new MageS.Inventory(MageS.Game);
        var spellbook = new MageS.Spellbook(MageS.Game);
        var spells = new MageS.Spells(MageS.Game);
        var spellcraft = new MageS.Spellcraft(MageS.Game);
        var monimations = new MageS.Monimations(MageS.Game);
        MageS.Game.init(inventory, spellbook, spells, spellcraft, animations, monimations);
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
    this.spellcraft = {};
    this.animations = {};
    this.monimations = {};
    this.gameStatus = $('#game-status').val();
    this.rawData = [];
    this.svg = {};
    this.turn = 0;
    this.worldType = 0;
    this.actionInProcess = false;
    /* CONFIG */
    this.fieldRadius = 5;
    this.cellSize = 32;
    this.itemSize = 34;
    this.mageInventorySize = 7 * this.itemSize + 18;
    this.mageMobileInventorySize = 6 * this.itemSize;
    this.animationTime = 300;
    this.battleFieldSize = (this.fieldRadius * 2 + 1) * this.cellSize;



    this.init = function (inventory, spellbook, spells, spellcraft, animations, monimations) {
        this.inventory = inventory;
        this.spellbook = spellbook;
        this.spells = spells;
        this.spellcraft = spellcraft;
        this.animations = animations;
        this.monimations = monimations;
        if ($(window).width() < 992) {
            this.device = 'tablet';
            if ($(window).width() < 768) {
                this.device = 'mobile';
            }
        }
        this.deviceInit();
        info( this.device);
        switch (this.gameStatus) {
            case 'mage-list':
                $('a#mage-create-button').on('click', function () {
                    MageS.Game.showCreateMagePopUp();
                });
                $('#create-mage-pop-up a.mage-type').on('click', function() {
                    var parent = $(this).parent();
                    parent.find('.mage-name').show();
                    parent.find('a.submit').show();
                });
                $('#create-mage-pop-up a.submit').on('click', function() {
                    var name = $(this).prev().prev().val();
                    var type = $(this).prev().val();
                    if (!name || !type) {
                        //display error
                        return false;
                    }
                    Ajax.json('/MageSurvival/createMage', {
                        data: 'name=' + name + '&type=' + type,
                        callBack : function(data){ MageS.Game.callback(data) }
                    });
                });
                break;
            case 'battle':

                this.initSVG();

                this.configureKeys();

                $('.inventory-shadow').on('click', function() {
                    MageS.Game.spellcraft.cancelCrafting();
                });


                break;
        }
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

            //$('.toggle-mage-info').on('click', function() {
            //    MageS.Game.toggleMageInfo();
            //});
            if (this.device !== 'pc') {
                //$('.right-panel').append($('#mobile-control-field'));
                //$('.right-panel').prepend($('.actions-container'));
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
        if (event.distance < 25) { return false; }
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
        this.startAction();
        this.spellbook.turnOffPatterns();
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
        Ajax.json('/MageSurvival/action', {
            data: 'action=' + actionName + '&data=' + dataString,
            callBack : function(data){ MageS.Game.callback(data) }
        });
    };

    this.callback = function(data) {
        if (data.action) {
            switch (data.action) {
                case 'mage-create':
                    window.location = '/MageSurvival';
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
            this.postMessages(data.game.messages);
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
                    if (actions[i].noAjax == undefined) {
                        obj.on('click', function () {
                            MageS.Game.action('objectInteract', '{"method":"' + $(this).data('method') + '"}')
                        });
                    }
                    if (!isFirstLoad) {
                        obj.find('a')
                            .css({'background-color': '#FCEBB6', 'opacity': 0.3})
                            .animate({'background-color': '#5E412F', 'opacity': 1}, {
                                queue: false,
                                duration: this.animationTime
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
                MageS.Game.spells.currentSpellData = {'targetX': 4, 'targetY': -1};
                MageS.Game.spells.startCast('Fireball');
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
        }
    };

    this.keyPressed = function(key) {
        info(key);
        var action = $('.actions .action.key-' + key);
        info(action);
        if (action.length) {
            action.click();
        }
    };

    this.buildUnits = function() {
        for(var y in this.rawData.units) {
            for(var x in this.rawData.units[y]) {
                this.drawUnit(this.rawData.units[y][x], x, y);
            }
        }
    };

    this.updateHealth = function(mage) {
        var total = mage.maxHealth;
        if (mage.armor !== undefined) {
            total += mage.armor;
        }
        var health = Math.round(mage.health / total * 100);
        var armor = 0;
        if (mage.armor !== undefined) {
            armor = Math.round(mage.armor / total * 100);
        }
        $('.health-bar .progress-bar-success').css('width', health + '%');
        $('.health-bar .progress-bar-warning').css('width', armor + '%');

        $('.health-bar .health-value').html(mage.health + 'HP');
        $('.health-bar .armor-value').html(armor);
    };
    //this.toggleMageInfo = function() {
    //    info('awdawd');
    //    if ($('.mage-profile').hasClass('active')) {
    //        this.hideMageInfo();
    //    } else {
    //        this.showMageInfo();
    //    }
    //};
    //this.hideMageInfo = function() {
    //    $('.mage-profile').removeClass('active');
    //};
    //this.showMageInfo = function() {
    //    $('.mage-profile').addClass('active').fadeIn();
    //};

    this.initSVG = function() {
        var url = '/images/game/mage/game-icons.svg';
        jQuery.get(url, function(data) {
            // Get the SVG tag, ignore the rest
            MageS.Game.svg = jQuery(data).find('svg');
            MageS.Game.buildMap();
            MageS.Game.drawMage(MageS.Game.rawData.mage);
            MageS.Game.updateActions(MageS.Game.rawData.actions, true);
            MageS.Game.spellbook.buildSpells();
            MageS.Game.inventory.buildItems();
            MageS.Game.buildUnits();
            MageS.Game.replaceMissingSvg();
            MageS.Game.updateHealth(MageS.Game.rawData.mage);


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

    this.startAction = function() {
        this.actionInProcess = true;
        $('.battle-border').addClass('action');
    };

    this.endAction = function() {
        this.actionInProcess = false;
        $('.battle-border').removeClass('action');
    };

    this.drawCell = function(cell, x, y) {
        var temaplate = $('#template-map-cell').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'x': x, 'y': y, 'class': cell});
        var obj = $(rendered);
        $('.battle-field').append(obj);
        obj.css({
            'margin-left' : (x * this.cellSize) + 'px',
            'margin-top' : (y * this.cellSize) + 'px',
        })
    };

    this.drawObject = function(object, x, y, target) {
        if (!target) {
            target = '.battle-field.current';
        }
        var temaplate = $('#template-object').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'id': object.id, 'type':object.type});
        var obj = $(rendered);
        var icon = $(this.svg).find('#' + object.config.icon + ' path');
        obj.find('svg').append(icon.clone());
        if (object.config.iconColor !== undefined) {
            obj.find('.svg').addClass(object.config.iconColor);
        }
        $(target + ' .cell.x-' + x + '.y-' + y).append(obj);
        return obj;
    };

    this.drawUnit = function(unit, x, y, target) {
        if (!target) {
            target = '.battle-field.current';
        }
        var temaplate = $('#template-unit').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'id': unit.id, 'type': unit.type});
        var obj = $(rendered);
        var icon = $(this.svg).find('#' + unit.icon + ' path');
        obj.find('svg').append(icon.clone());
        $(target + ' .cell.x-' + x + '.y-' + y).append(obj);
        return obj;
    };

    this.drawMage = function(mageConf) {
        var temaplate = $('#template-mage').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'d': mageConf.d});
        var obj = $(rendered);
        $('.mage-container').prepend(obj);

    };

    this.postMessages = function(messages) {
        for (var i in messages) {
            var message = messages[i];
            this.postMessage(message);
        }
    };
    this.postMessage = function (message) {
        info(message);
        if (message.type === undefined) {

        }
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


    this.showCreateMagePopUp = function() {
        $('#create-mage-pop-up').show();
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

