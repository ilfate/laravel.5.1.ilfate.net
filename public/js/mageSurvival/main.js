/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */

function MageS () {

}
MageS = new MageS();

$(document).ready(function() {
    if ($('body.mage-survival').length) {
        MageS.Game = new MageS.Game();
        MageS.Animations = new MageS.Animations(MageS.Game);
        var inventory = new MageS.Inventory(MageS.Game);
        var spellbook = new MageS.Spellbook(MageS.Game);
        var spells = new MageS.Spells(MageS.Game);
        MageS.Game.init(inventory, spellbook, spells);
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
    this.inventory = {};
    this.spellbook = {};
    this.spells = {};
    this.gameStatus = $('#game-status').val();
    this.rawData = [];
    this.worldType = 0;
    this.actionInProcess = false;
    /* CONFIG */
    this.fieldRadius = 5;
    this.cellSize = 40;
    this.animationTime = 400;
    this.battleFieldSize = (this.fieldRadius * 2 + 1) * this.cellSize;



    this.init = function (inventory, spellbook, spells) {
        this.inventory = inventory;
        this.spellbook = spellbook;
        this.spells = spells;
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
                this.buildMap();
                this.spellbook.buildSpells();
                this.configureKeys();

                $('a.craft-spell-button').on('click', function() {
                    MageS.Game.spellbook.showSpellCrafting();
                });
                break;
        }
    }

    this.buildMap = function() {
        this.rawData = mageSurvivalData;
        info(this.rawData.objects);
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
        this.drawMage(this.rawData.mage);
        this.updateActions(this.rawData.actions);
    };



    this.action = function(action, data) {
        if (this.actionInProcess) {
            return;
        }
        this.actionInProcess = true;
        var actionName = '';
        var dataString = '';
        switch (action) {
            case 'move':
                actionName = 'move';
                dataString = '[]';
                break;
            case 'moveBack':
                actionName = 'moveBack';
                dataString = '[]';
                break;
            case 'rotateLeft':
                actionName = 'rotate';
                dataString = '{"d":"left"}';
                break;
            case 'rotateRight':
                actionName = 'rotate';
                dataString = '{"d":"right"}';
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

    this.moveSwitch = [
        function (x, y) { return {'x': x, 'y': y - 1}},
        function (x, y) { return {'x': x + 1, 'y': y}},
        function (x, y) { return {'x': x, 'y': y + 1}},
        function (x, y) { return {'x': x - 1, 'y': y}}
    ];
    this.moveDeleteCells = [
        function (x, y) { return {'x': x, 'y': -y}},
        function (x, y) { return {'x': -x, 'y': y}},
        function (x, y) { return {'x': x, 'y': -y}},
        function (x, y) { return {'x': -x, 'y': y}}
    ];
    this.moveAreaCoordinat = [
        {'margin-top':(this.fieldRadius + 1) * this.cellSize + 'px'},
        {'margin-left':(this.fieldRadius - 1) * this.cellSize + 'px'},
        {'margin-top':(this.fieldRadius - 1) * this.cellSize + 'px'},
        {'margin-left':(this.fieldRadius + 1) * this.cellSize + 'px'}
    ];
    this.moveAreaCoordinatBack = [
        {'margin-top':(this.fieldRadius) * this.cellSize + 'px'},
        {'margin-left':(this.fieldRadius) * this.cellSize + 'px'},
        {'margin-top':(this.fieldRadius) * this.cellSize + 'px'},
        {'margin-left':(this.fieldRadius) * this.cellSize + 'px'}
    ];
    this.moveMageCoordinat = [
        {'margin-top' : -this.cellSize + 'px'},
        {'margin-left' : this.cellSize + 'px'},
        {'margin-top' : this.cellSize + 'px'},
        {'margin-left' : -this.cellSize + 'px'}
    ];

    this.callback = function(data) {
        if (data.action) {
            switch (data.action) {
                case 'mage-create':
                    window.location = '/MageSurvival';
                    break;
                case 'move':
                    this.moveAnimate(data);
                    break;
                case 'rotate':
                    this.rotateAnimate(data);
                    break;
                case 'objectInteract':
                    this.inventory.addItems(data.game);
                    break;
                case 'spellCraft':
                    this.spellbook.spellCrafted(data);
                    break;
                case 'spell':
                    this.spells.castSpell(data);
                    break;
            }
        }
        info(data);
        this.updateActions(data.game.actions);
        if (data.game.items) {
            this.inventory.updateItems(data.game.items);
        }
        if (data.game.spells) {
            this.spellbook.updateSpells(data.game.spells);
        }
        if (data.game.messages) {
            this.postMessages(data.game.messages);
        }

    };

    this.moveAnimate = function(data) {
        var d = data.game.mage.d;
        var toDeleteCells = [];
        for (var y in data.game.map) {
            for (var x in data.game.map[y]) {
                var deleteCellCoordinats = this.moveDeleteCells[d](parseInt(x), parseInt(y));
                toDeleteCells.push($('.cell.x-'+deleteCellCoordinats.x+'.y-'+deleteCellCoordinats.y));
                var position = this.moveSwitch[d](parseInt(x), parseInt(y));
                var temaplate = $('#template-map-cell').html();
                Mustache.parse(temaplate);
                var rendered = Mustache.render(temaplate, {'x': position.x, 'y': position.y, 'class': data.game.map[y][x]});
                var obj = $(rendered);
                $('.battle-field').append(obj);
                obj.css({
                    'margin-left' : (position.x * this.cellSize) + 'px',
                    'margin-top' : (position.y * this.cellSize) + 'px',
                });
            }
        }

        for (var y in data.game.objects) {
            for (var x in data.game.objects[y]) {
                var position = this.moveSwitch[d](parseInt(x), parseInt(y));
                var temaplate = $('#template-object').html();
                Mustache.parse(temaplate);
                var rendered = Mustache.render(temaplate, {'id': data.game.objects[y][x].id});
                var obj = $(rendered);
                $('.battle-field .cell.x-' + position.x + '.y-' + position.y).append(obj);
            }
        }

        that = this;
        $('.battle-field .mage').animate(this.moveMageCoordinat[d],{'duration': this.animationTime});
        $('.battle-field').animate(this.moveAreaCoordinat[d],{'duration': this.animationTime,
            complete:function(){
                for(var i in toDeleteCells) {
                    toDeleteCells[i].remove();
                }
                $('.battle-field').css(that.moveAreaCoordinatBack[d]);
                $('.battle-field .mage').css({margin : 0});
                $('.battle-field .cell').each(function(){
                    var newX, newY;
                    var cellX = newX = $(this).data('x');
                    var cellY = newY = $(this).data('y');
                    if (d == 0 || d == 2) {
                        var range = that.cellSize;
                        if (d == 2) {
                            range = - range;
                            newY--;
                        } else {
                            newY++;
                        }
                        $(this).css('margin-top', parseInt($(this).css('margin-top')) + range + 'px')
                    } else {
                        var range = that.cellSize;
                        if (d == 1) {
                            range = -range;
                            newX--;
                        } else {
                            newX++;
                        }
                        $(this).css('margin-left', parseInt($(this).css('margin-left')) + range + 'px');
                    }
                    $(this)
                        .removeClass('x-' + cellX)
                        .removeClass('y-' + cellY)
                        .addClass('x-' + newX)
                        .addClass('y-' + newY)
                        .data('x', newX)
                        .data('y', newY);
                    that.actionInProcess = false;
                })
            }});
    }

    this.rotateAnimate = function(data) {
        var d = 0;
        var oldD = 0;
        switch (data.game.d) {
            case 1: d = 90; break;
            case 2: d = 180; break;
            case 3: d = 270; break;
        }
        switch (data.game.oldD) {
            case 1: oldD = 90; break;
            case 2: oldD = 180; break;
            case 3: oldD = 270; break;
        }
        if (oldD == 270 && d == 0) {
            oldD = -90;
        }
        if (oldD == 0 && d == 270) {
            oldD = 360;
        }
        var el = $('.battle-field .mage');
        that = this;
        el.removeClass('d-' + data.game.oldD);
        el.animateRotate(oldD, d, this.animationTime, "swing", function(){
            $(this).addClass('d-' + data.game.d).data('d', data.game.d);
            that.actionInProcess = false;
        });
    }

    this.updateActions = function (actions) {
        info(actions);
        var actionsEl = $('.actions');
        actionsEl.html('');
        if (!actions) {
            return;
        }
        for (var i in actions) {
            var temaplate = $('#template-action-button').html();
            Mustache.parse(temaplate);
            var rendered = Mustache.render(temaplate, {'name': actions[i].name});
            var obj = $(rendered);
            actionsEl.append(obj);
            obj.on('click', function() {
                MageS.Game.action('objectInteract', '{"method":"' + actions[i].method + '"}')
            })
        }
    };



    this.drawCell = function(cell, x, y) {
        var temaplate = $('#template-map-cell').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'x': x, 'y': y, 'class': cell});
        var obj = $(rendered);
        $('.battle-field').append(obj);
        obj.css({
            'margin-left' : (x * 40) + 'px',
            'margin-top' : (y * 40) + 'px',
        })
    };

    this.drawObject = function(object, x, y) {
        var temaplate = $('#template-object').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'id': object.id});
        var obj = $(rendered);
        $('.battle-field .cell.x-' + x + '.y-' + y).append(obj);
    };

    this.drawMage = function(mageConf) {
        var temaplate = $('#template-mage').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'d': mageConf.d});
        var obj = $(rendered);
        $('.battle-field').append(obj);

    };

    this.postMessages = function(messages) {
        for (var i in messages) {
            var message = messages[i];
            info(messages[i]);
            if (message.type === undefined) {

            }
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
            info(event.keyCode);
            switch (event.keyCode) {
                case 40: // up
                case 1094:
                    MageS.Game.action('move');
                    break;
                case 37: // left
                case 1092:
                    MageS.Game.action('rotateLeft');
                    break;
                case 38: // down
                case 1099:
                    MageS.Game.action('moveBack');
                    break;
                case 39: // right
                case 1074:
                    MageS.Game.action('rotateRight');
                    break;

                case 13:  // Enter
                    break;
                case 119 : // w
                    MageS.Game.action('move');
                    break;
                case 97 : // a
                    MageS.Game.action('rotateLeft');
                    break;
                case 115 : // s
                    MageS.Game.action('moveBack');
                    break;
                case 100 : // d
                    MageS.Game.action('rotateRight');
                    break;
                case 32 :  // space
                    break;
                case 101 :  // e
                    //CanvasActions.robot.destroyWall();
                    break;
                case 114 :  // r
                    break;
                case 102 :  // f
                    break;
                case 0 :                  //// For Mozila
                    switch (event.charCode) {
                        case 119 : // w
                            MageS.Game.action('move');
                            break;
                        case 97 : // a
                            MageS.Game.action('rotateLeft');
                            break;
                        case 115 : // s
                            MageS.Game.action('moveBack');
                            break;
                        case 100 : // d
                            MageS.Game.action('rotateRight');
                            break;
                        case 32 :  // space
                            break;
                        case 101 :  // e
                            //CanvasActions.robot.destroyWall();
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

