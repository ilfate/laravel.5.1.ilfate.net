/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Spellbook = function (game) {
    this.game = game;
    this.spellCraftProcess = {};

    this.buildSpells = function() {
        $('.spellBook .spell').each(function() {
            var data = $(this).data('values');
            MageS.Game.spellbook.buildSpell($(this), data);
            MageS.Game.spellbook.addSpellDescription(data, $(this));
        });
        $('.pattern-field .pattern-cell').on('click', function() {MageS.Game.spellbook.patternClick($(this))});


    };

    this.buildSpell = function(spell, values) {
        if (values.viewData.noTargetSpell) {
            spell.on('click', function() {
                MageS.Game.action('spell', '{"id":"' + $(this).data('id') + '"}')
            });
        } else if (values.viewData.directTargetSpell) {
            switch (values.viewData.directTargetSpell) {
                case 'enemy':
                    //find all enemies/
                    // hgihtlight them
                    spell.on('click', function() {
                        MageS.Game.spellbook.showEnemyTargets($(this));
                    });
                    break;
            }
        } else if (values.pattern) {
            // display the pattern
            spell.on('click', function() {
                MageS.Game.spellbook.showPattern($(this), values.pattern);
            });
        }
    };

    this.updateSpells = function(spells) {
        for(var schoolId in spells) {
            for(var id in spells[schoolId]) {
                var spell = spells[schoolId][id];
                var typeEl = $('.spells-tab.school-' + schoolId);
                var existingEl = typeEl.find('.spell.id-' + id);
                if (spell.status == 'new') {
                    //create new spell
                    if (!typeEl.length)
                    {
                        var tabId = 'spells-tab-' + schoolId;
                        $('.spellBook .tab-content').append(
                            $('<div role="tabpanel" class="tab-pane spells-tab"></div>').addClass('school-' +schoolId)
                                .attr('id', tabId)
                        );
                        $('.spellBook .nav.nav-tabs').append(
                            $('<li role="presentation"><a href="#' + tabId + '" aria-controls="' + tabId + '" role="tab" data-toggle="tab">' + schoolId + '</a></li>')
                        );
                    }
                    var temaplate = $('#template-inventory-spell').html();
                    Mustache.parse(temaplate);
                    var rendered = Mustache.render(temaplate, {
                        'id': id,
                        'name': spell.name,
                        'quantity': spell.config.usages,
                    });
                    var obj = $(rendered);
                    $('.spells-tab.school-' + schoolId).append(obj);
                    obj.tooltip();
                    MageS.Game.spellbook.buildSpell(obj, spell);
                    MageS.Game.spellbook.addSpellDescription(spell, obj);
                } else {
                    if (existingEl.length) {
                        //add item
                        if (spell.status == 'deleted') {
                            existingEl.remove();
                        } else {
                            var newQuantity = spell.config.usages;
                            if (newQuantity > 0) {
                                existingEl.html(newQuantity);
                            } else {
                                existingEl.remove();
                            }
                        }
                    }
                }


            }
        }
    };

    this.checkForActiveSpells = function(spell) {
        var activeSpell = $('.spell.active');
        if (activeSpell.length) {
            this.turnOffPatterns();
            activeSpell.removeClass('active');
            if (activeSpell.data('id') == spell.data('id')) {
                return false;
            }
        }
        return true;
    };

    this.showPattern = function (spell, pattern) {
        if (!this.checkForActiveSpells(spell)) {
            return;
        }
        spell.addClass('active');
        mageDirection = 0;
        for(var d = 0; d < 4; d++) {
            for (var key in pattern) {
                var x = pattern[key][0];
                var y = pattern[key][1];
                var relativeCoords = this.rotatePatternCoordinats(x, y, d)
                var patternCell = $('.pattern-cell.x-' + relativeCoords[0] + '.y-' + relativeCoords[1]);
                patternCell.addClass('group-d-' + d).data('d', d);
                if (d == mageDirection) {
                    patternCell.addClass('active');
                } else {
                    patternCell.addClass('visible');
                }
                patternCell.on('mouseenter', function() {MageS.Game.spellbook.patternSwitchDirection($(this))});
            }
        }
    };
    this.rotatePatternCoordinats = function(x, y, d) {
        switch (d) {
            case 0: return [x, y];
            case 1: return [-y, x];
            case 2: return [-x, -y];
            case 3: return [y, -x];
        }
    };
    this.patternSwitchDirection = function(patternCell) {
        if (patternCell.hasClass('active')) {return;}
        var d = patternCell.data('d');
        $('.pattern-cell.active').removeClass('active').addClass('visible');
        $('.pattern-cell.group-d-' + d).removeClass('visible').addClass('active');

    };
    this.turnOffPatterns = function() {
        for(var d = 0; d < 4; d++) {
            $('.pattern-cell.group-d-' + d)
                .removeClass('group-d-' + d)
                .data('d', '');
        }
        $('.pattern-cell.active').removeClass('active');
        $('.pattern-cell.visible').removeClass('visible');
    };

    this.patternClick = function(patternCell) {
        var spell = $('.spell.active');
        if (spell.length !== 1) {
            info('ERROR not one spell active');
        }
        spell.removeClass('active');
        var d = patternCell.data('d');
        var x = patternCell.data('x');
        var y = patternCell.data('y');
        MageS.Game.action('spell', '{"id":"' + spell.data('id') + '","d":"' + d + '","x":"' + x + '","y":"' + y + '"}');
    };

    this.showEnemyTargets = function(spell) {
        if (!this.checkForActiveSpells(spell)) {
            return;
        }
        spell.addClass('active');

        $('.battle-field.current .unit').each(function () {
            var cellElem = $(this).parent('.cell');
            var x = cellElem.data('x');
            var y = cellElem.data('y');
            var patternCell = $('.pattern-cell.x-' + x + '.y-' + y);
            patternCell.addClass('active');
        });
    };

    this.addSpellDescription = function(data, spell) {
        var temaplate = $('#template-spell-tooltip').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'id': data.id, 'name': data.name, 'description' : data.viewData.description});
        var obj = $(rendered);
        $('.tooltip-helper-area').append(obj);
        spell.on({
            'mouseenter': function() {
                var id = $(this).data('id');
                $('.tooltip-helper-area .spell-tooltip.id-' + id).show();
            },
            'mouseleave':function() {
                var id = $(this).data('id');
                $('.tooltip-helper-area .spell-tooltip.id-' + id).hide();
            }
        });
    };

    this.spellCrafted = function(data) {
        this.spellCraftProcess = [];
        this.endSpellCraftAnimations();


        this.game.actionInProcess = false;
    };

    this.startSpellCraftAnimations = function () {
        console.info('Start spell craft animations');
    };
    this.endSpellCraftAnimations = function () {
        console.info('End spell craft animations');
    };

    this.showSpellCrafting = function() {
        if (!$('.inventory .items-tab.carrier .item').length) {
            this.itemsMessage('You have no Carrier to create a spell');
            return;
        }
        var itemsCount = 0;
        $('.inventory .items-tab.ingredient .item').each(function() {
            itemsCount += parseInt($(this).html());
        });
        if (itemsCount < 3) {
            this.itemsMessage('You need more then 3 ingredients to create a spell');
            return;
        }
        var carrierTab = this.craftingItemsTab('.items-tab.carrier');
        carrierTab.addClass('spell-craft-1');
        this.showSpellCraftHelperStep1();
        var newItems = carrierTab.find('.item');
        newItems.tooltip();
        newItems.on('click', function() {
            MageS.Game.spellbook.SpellCraftStep2($(this));
        });
    };

    this.SpellCraftStep2 = function(carrierEl) {
        this.spellCraftProcess['carrier'] = carrierEl;
        $('.spell-craft-1').hide(200, function(){ $(this).remove(); });
        var ingridientsTab = this.craftingItemsTab('.items-tab.ingredient');
        ingridientsTab.addClass('spell-craft-2');
        $('.helper-spell-craft-step-1').hide(200, function(){ $(this).remove(); });
        this.showSpellCraftHelperStep2();
        var newItems = ingridientsTab.find('.item');
        newItems.tooltip();
        newItems.on('click', function() {
            MageS.Game.spellbook.SpellCraftStep3($(this));
        });
    };

    this.SpellCraftStep3 = function(ingridientEl) {
        if (!this.spellCraftProcess['ingridients']) {
            this.spellCraftProcess['ingridients'] = [];
        }
        if (this.spellCraftProcess['ingridients'].length > 3) {
            return;
        }
        $('.helper-spell-craft-step-2 .helper-spell-craft-step-2-error').remove();
        var newId = ingridientEl.data('id');
        var numberOfSameIds = 0;
        for (var i in this.spellCraftProcess['ingridients']) {
            var id = this.spellCraftProcess['ingridients'][i].data('id');
            if (id == newId) {
                numberOfSameIds++;
            }
        }
        if (parseInt(ingridientEl.html()) < numberOfSameIds + 1) {
            this.addCraftingMessageStep3('You don`t have enough of that ingredient');
            return;
        }
        this.spellCraftProcess['ingridients'].push(ingridientEl);
        if (this.spellCraftProcess['ingridients'].length == 3) {
            $('.spell-craft-2').hide(200, function(){ $(this).remove(); });
            $('.helper-spell-craft-step-2').hide(200, function(){ $(this).remove(); });

            this.showSpellCraftInfo();

        } else {
            ingridientEl.css('box-shadow', '0 2px 3px rgba(0, 0, 0, 0.23), 0 2px 3px rgba(0, 0, 0, 0.19)');
            var number = $('.select-mode-ingredients');
            number.html(3 - this.spellCraftProcess['ingridients'].length);
            number.css('font-size', '1.5rem').animate({'font-size' : '1rem'});
        }
    };

    this.createSpellAction = function()
    {
        $('.spell-craft-info').remove();
        this.startSpellCraftAnimations();
        var data = {};
        var carrier = this.spellCraftProcess.carrier.data('id');
        var items = [];
        for(var i in this.spellCraftProcess.ingridients) {
            items.push(this.spellCraftProcess.ingridients[i].data('id'))
        }
        data = '{"carrier" : "' + carrier + '", "ingredients": ["' + items[0] + '","' + items[1] + '","' + items[2] + '"]}';
        this.game.action('craftSpell', data);
    };


    this.craftingItemsTab = function(tabQuery) {
        var carrierTab = $(tabQuery).clone();
        carrierTab.show().removeAttr('role').removeAttr('id').removeClass('tab-pane')
            .css('opacity', 0);
        $('.craft-spell-overlay').append(carrierTab);
        carrierTab.animate({
            'width': (this.game.battleFieldSize / 2) + 'px',
            'height': (this.game.battleFieldSize / 2) + 'px',
            'margin-top': this.game.battleFieldSize / 4 + 'px',
            'margin-left': this.game.battleFieldSize / 4 + 'px',
            'opacity': 1
        });
        return carrierTab;
    };

    this.showSpellCraftHelperStep1 = function() {
        var temaplate = $('#template-helper-spell-craft-step-1').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate);
        var obj = $(rendered);
        $('.craft-spell-overlay').append(obj);
    };
    this.showSpellCraftHelperStep2 = function() {
        var temaplate = $('#template-helper-spell-craft-step-2').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate);
        var obj = $(rendered);
        $('.craft-spell-overlay').append(obj);
    };

    this.showSpellCraftInfo = function() {
        var temaplate = $('#template-spell-craft-info').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate);
        var obj = $(rendered);
        $('.craft-spell-overlay').append(obj);
        $('.confirm-create-spell').on('click', function(){
            MageS.Game.spellbook.createSpellAction();
        });
    };

    this.addCraftingMessageStep3 = function(message) {
        $('.helper-spell-craft-step-2').append($('<p class="large-text helper-spell-craft-step-2-error">' + message + '</p>'));
        $('.helper-spell-craft-step-2-error').animate({'font-size' : '1rem'});
    };
};

