/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Spellbook = function (game) {
    this.game = game;
    this.craftingIsInProgress = false;
    this.spellCraftProcess = {};
    this.spellsPatterns = {};

    this.buildSpells = function() {

        var template = $('#template-inventory-spell').html();
        Mustache.parse(template);
        var spellsEl = $('.spellBook .spells');
        var spells = spellsEl.data('spells');
        for (var id in spells) {
            var obj = this.renderSpell(template, spells[id]);
            spellsEl.append(obj);
            MageS.Game.spellbook.buildSpell(obj);
            MageS.Game.spellbook.addSpellDescription(spells[id], obj);
        }
        spellsEl.data('spells', '').attr('data-spells', '');

        // build filters
        $('.spell-filter').each(function(){
            $(this).on('click', function(){
                MageS.Game.spellbook.filterSpells($(this));
            })
        });

        $('.pattern-field .pattern-cell').on('click', function() {MageS.Game.spellbook.patternClick($(this))});


    };

    this.buildSpell = function(spellEl) {
        spellEl.on('click', function() {
            MageS.Game.spellbook.spellClick($(this));
        });
    };

    this.spellClick = function(spellEl) {
        if (spellEl.hasClass('cooldown')) {
            info('This spell is on cooldown');
            return;
        }
        var spellType = spellEl.data('spell-type');
        var targetType = spellEl.data('target-type');
        if (spellType == 'noTargetSpell') {
            MageS.Game.action('spell', '{"id":"' + spellEl.data('id') + '"}');
        } else if (spellType == 'directTargetSpell') {
            switch (targetType) {
                case 'enemy':
                    //find all enemies/
                    // hgihtlight them
                    MageS.Game.spellbook.showEnemyTargets(spellEl);
                    break;
            }
        } else if (spellType == 'pattern') {
            // display the pattern
            MageS.Game.spellbook.showPattern(spellEl, this.spellsPatterns[spellEl.data('id')]);
        }
    };

    this.updateSpells = function(spells) {
        for(var id in spells) {
            var spell = spells[id];
            var existingEl = $('.spell.id-' + id);
            if (spell.status == 'new') {
                //create new spell
                var template = $('#template-inventory-spell').html();
                Mustache.parse(template);
                var obj = this.renderSpell(template, spell);
                $('.spellBook .spells').append(obj);
                obj.tooltip();
                MageS.Game.spellbook.buildSpell(obj);
                MageS.Game.spellbook.addSpellDescription(spell, obj);

                //do wee need to create filter?
                var filter = $('.spells-filter-panel .spell-filter.school-' + spell.schoolId);
                if (filter.length < 1) {
                    info('create new filter');
                    // create new filter
                    //$schoolConfig['icon']
                    var template = $('#template-inventory-spell-filter').html();
                    Mustache.parse(template);
                    var rendered = Mustache.render(template, {
                        'schoolId': spell.schoolId,
                        'class': spell.schoolViewData.class,
                    });
                    var objFilter = $(rendered);
                    info(objFilter);
                    var icon = $(this.game.svg).find('#' + spell.schoolViewData.icon + ' path');
                    objFilter.find('svg').append(icon.clone());
                    objFilter.on('click', function(){
                        MageS.Game.spellbook.filterSpells($(this));
                    });
                    $('.spells-filter-panel').append(objFilter);
                }
                $('.spellBook .spells').append(obj);
            } else {
                if (existingEl.length) {
                    //add item
                    if (spell.status == 'deleted') {
                        existingEl.remove();
                    } else {
                        var newQuantity = spell.config.usages;
                        if (newQuantity > 0) {
                            existingEl.find('.value').html(newQuantity);
                        } else {
                            existingEl.remove();
                        }
                        existingEl.data('cooldown-mark', spell.config.cooldownMark);
                        //cooldowns
                        if (!existingEl.hasClass('cooldown')) {
                            if (this.game.turn < spell.config.cooldownMark) {
                                this.addCooldown(existingEl);
                            }
                        } else {
                            if (this.game.turn >= spell.config.cooldownMark) {
                                this.removeCooldown(existingEl);
                            }
                        }
                    }
                }
            }
        }
    };

    this.renderSpell = function(template, spell) {
        var spellType = '';
        var targetType = '';
        if (spell.viewData.noTargetSpell) {
            spellType = 'noTargetSpell';
        } else if (spell.viewData.directTargetSpell) {
            spellType = 'directTargetSpell';
            targetType = spell.viewData.directTargetSpell;
        } else if(spell.pattern) {
            spellType = 'pattern'
            this.spellsPatterns[spell.id] = spell.pattern;
        }
        var icon = $(this.game.svg).find('#' + spell.viewData.iconClass + ' path');
        //<use xlink:href="/images/game/mage/game-icons.svg#{{icon-class}}"></use>
        var rendered = Mustache.render(template, {
            'id': spell.id,
            'name': spell.name,
            'quantity': spell.config.usages,
            'cooldownMark': spell.config.cooldownMark,
            'spellType': spellType,
            'targetType': targetType,
            'school': spell.schoolId,
        });
        var obj = $(rendered);
        obj.find('svg').append(icon.clone());
        if (spell.viewData.iconColor !== undefined) {
            obj.addClass(spell.viewData.iconColor);
            //obj.find('path').css('fill', spell.viewData.iconColor)
        }
        if (this.game.turn < spell.config.cooldownMark) {
            // this spell is on cooldown
            this.addCooldown(obj);
        }
        return obj;
    };

    this.turn = function() {
        $('.spellBook .spell.cooldown').each(function() {
            if ($(this).data('cooldown-mark') <= MageS.Game.turn) {
                MageS.Game.spellbook.removeCooldown($(this));
            } else {
                MageS.Game.spellbook.stepCooldown($(this));
            }
        });
    };

    this.addCooldown = function(spellEl) {
        spellEl.addClass('cooldown');
    };

    this.removeCooldown = function(spellEl) {
        info('removing cooldown');
        spellEl.removeClass('cooldown');
    };

    this.stepCooldown = function(spellEl) {
        //info('cooldown step');
    };

    this.filterSpells = function(filterEl) {
        var activeFilter = $('.spell-filter.active');
        $('.spell-filter.active').removeClass('active');
        $('.spellBook .spell.filtered-out').removeClass('filtered-out');
        if (activeFilter.length && activeFilter.data('school') == filterEl.data('school')) {
            return;
        }
        filterEl.addClass('active');
        $('.spellBook .spell:not(.school-' + filterEl.data('school') + ')').addClass('filtered-out');
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
        if (this.game.actionInProcess == true) {
            info('Can`t show pattern while action is in process');
            return;
        }
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
        $('.tooltip-spell-area').append(obj);
        if (this.game.device == 'pc') {
            spell.on({
                'mouseenter': function () {
                    var id = $(this).data('id');
                    $('.tooltip-spell-area .spell-tooltip.id-' + id).show();
                },
                'mouseleave': function () {
                    var id = $(this).data('id');
                    $('.tooltip-spell-area .spell-tooltip.id-' + id).hide();
                }
            });
        }
    };

    this.spellCrafted = function(data) {
        this.spellCraftProcess = [];
        this.endSpellCraftAnimations();

    };

    this.startSpellCraftAnimations = function () {
        console.info('Start spell craft animations');
    };
    this.endSpellCraftAnimations = function () {
        console.info('End spell craft animations');
        this.game.endAction();
    };

    this.cancelCrafting = function () {
        $('.inventory').removeClass('craft');
        $('.inventory-shadow').animate({'opacity': 0}, {'duration': this.game.animationTime / 3,'complete':function(){
            $(this.hide);
        }});
        this.game.inventory.turnOffFilters();
    };

    this.showSpellCrafting = function() {
        if (!$('.inventory .item.type-carrier').length) {
            this.itemsMessage('You have no Carrier to create a spell');
            return;
        }
        var itemsCount = 0;
        $('.inventory .item.type-ingredient .value').each(function() {
            itemsCount += parseInt($(this).html());
        });
        if (itemsCount < 3) {
            this.itemsMessage('You need more then 3 ingredients to create a spell');
            return;
        }
        this.craftingIsInProgress = true;
        $('.inventory-shadow').show().animate({'opacity': 0.8}, {'duration': this.game.animationTime / 3})
        $('.inventory').addClass('craft');
        MageS.Game.inventory.filterItems($('.items-filter.name-carrier'));

        this.showSpellCraftHelperStep1();
         //   MageS.Game.spellbook.SpellCraftStep2($(this));
    };

    this.itemClick = function(itemObj) {
        if (itemObj.hasClass('type-carrier')) {
            this.SpellCraftStep2(itemObj);
        } else if (itemObj.hasClass('type-ingredient')) {
            this.SpellCraftStep3(itemObj);
        }
    };

    this.SpellCraftStep2 = function(carrierEl) {
        this.spellCraftProcess['carrier'] = carrierEl;
        //$('.spell-craft-1').hide(200, function(){ $(this).remove(); });
        //var ingridientsTab = this.craftingItemsTab('.items-tab.ingredient');
        //ingridientsTab.addClass('spell-craft-2');
        MageS.Game.inventory.filterItems($('.items-filter.name-ingredient'));
        $('.helper-spell-craft-step-1').hide(200, function(){ $(this).remove(); });
        this.showSpellCraftHelperStep2();
        //var newItems = ingridientsTab.find('.item');
        //newItems.tooltip();
        //newItems.on('click', function() {
        //    MageS.Game.spellbook.SpellCraftStep3($(this));
        //});
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
        if (parseInt(ingridientEl.find('.value').html()) < numberOfSameIds + 1) {
            this.addCraftingMessageStep3('You don`t have enough of that ingredient');
            return;
        }
        this.spellCraftProcess['ingridients'].push(ingridientEl);
        if (this.spellCraftProcess['ingridients'].length == 3) {
            $('.spell-craft-2').hide(200, function(){ $(this).remove(); });
            $('.helper-spell-craft-step-2').hide(200, function(){ $(this).remove(); });

            this.showSpellCraftInfo();

        } else {
            ingridientEl.find('path').css({'fill': '#069E2D', transition: "2.0s"});
            var number = $('.select-mode-ingredients');
            number.html(3 - this.spellCraftProcess['ingridients'].length);
            number.css('color', '#FF8360').animate({'color' : '#FFF'});
        }
    };

    this.createSpellAction = function()
    {
        $('.spell-craft-info').remove();
        this.startSpellCraftAnimations();
        this.cancelCrafting();
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

