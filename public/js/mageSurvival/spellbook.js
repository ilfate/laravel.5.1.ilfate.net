/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Spellbook = function (game) {
    this.game = game;
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
        if (this.game.spellcraft.isBlenderActive) {
            this.game.spellcraft.itemClick(spellEl);
            return;
        }
        if (spellEl.hasClass('cooldown') && this.game.device !== 'mobile') {
            info('This spell is on cooldown');
            return;
        }
        if (this.game.animations.isAnimationsRunning) {
            this.game.monimations.shake(spellEl);
            return;
        }
        var spellType = spellEl.data('spell-type');
        var targetType = spellEl.data('target-type');
        var isCastAllowed = false;
        var isCreateHiddenDescription = false;
        var isAddCastToDescription = false;
        if (this.game.device == 'mobile') {
            if (spellEl.hasClass('active')) {
                isCastAllowed = true;
            } else {
                isCreateHiddenDescription = true;
            }
        } else if(this.game.device == 'pc') {
            isCastAllowed = false;

        } else if(this.game.device == 'tablet') {
            isCastAllowed = false;
        }
        switch (spellType) {
            case 'noTargetSpell':
                if (isCastAllowed && !spellEl.hasClass('cooldown')) {
                    this.castSpellStart(spellEl, '{"id":"' + spellEl.data('id') + '"}');
                    spellEl.removeClass('active');
                } else {
                    if (this.checkForActiveSpells(spellEl)) {
                        isAddCastToDescription = true;
                        spellEl.addClass('active');
                        this.activateSpellTooltipPermanent(spellEl);
                    }
                }
                break;
            case 'directTargetSpell':
                isAddCastToDescription = true;
                switch (targetType) {
                    case 'enemy':
                        //find all enemies/
                        // hgihtlight them
                        MageS.Game.spellbook.showEnemyTargets(spellEl);
                        break;
                }
                break;
            case 'pattern':
                isAddCastToDescription = true;
                // display the pattern
                MageS.Game.spellbook.showPattern(spellEl, this.spellsPatterns[spellEl.data('id')]);
                break;
        }
        if (isCreateHiddenDescription) {
            this.createHiddenDescription(spellEl);
        }
        if (isAddCastToDescription) {
            this.addCastToDescription(spellEl);
        }
    };
    this.spellCastClick = function() {
        var spellEl = $('.spell.active');
        this.castSpellStart(spellEl, '{"id":"' + spellEl.data('id') + '"}');
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
                this.game.monimations.scaleIn(obj, 2000);
                obj.tooltip();
                MageS.Game.spellbook.buildSpell(obj);
                MageS.Game.spellbook.addSpellDescription(spell, obj);

                //do wee need to create filter?
                var filter = $('.spells-filter-panel .spell-filter.school-' + spell.schoolId);
                if (filter.length < 1) {
                    // create new filter
                    var template = $('#template-inventory-spell-filter').html();
                    Mustache.parse(template);
                    var rendered = Mustache.render(template, {
                        'schoolId': spell.schoolId,
                        'class': spell.schoolViewData.class,
                    });
                    var objFilter = $(rendered);
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
                    //add spell
                    if (spell.status == 'deleted') {
                        this.deleteSpellFromInventory(existingEl);
                    } else {
                        var newQuantity = spell.config.usages;
                        if (newQuantity > 0) {
                            existingEl.find('.value').html(newQuantity);
                            existingEl.data('cooldown-mark', spell.config.cooldownMark);
                            existingEl.find('.value').css({'background-color': '#FF8360', 'color':'#fff'}).animate({
                                'background-color': '#FCEBB6', 'color':'#5E412F'
                            }, {'duration': 2000});
                            MageS.Game.monimations.skweeze(existingEl);
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
                        } else {
                            this.deleteSpellFromInventory(existingEl);
                        }
                    }
                }
            }
        }
    };

    this.deleteSpellFromInventory = function(spellEl) {
       spellEl.animate({
           bullshit: 100
       },{
           step: function(now,fx) {
               var x = now / 100;
               $(this)[0].style.transform = 'scale(' + (1 - x) + ') rotate(' + x * 360 + 'deg)';
           },
           duration:1500, 'easing':'easeInBounce',
           'complete': function() {
               $(this).remove();
           }
       });
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
            'viewData': spell.viewData,
            'quantity': spell.config.usages,
            'cooldownMark': spell.config.cooldownMark,
            'spellType': spellType,
            'targetType': targetType,
            'school': spell.schoolId,
        });
        var obj = $(rendered);
        obj.find('svg').append(icon.clone());
        if (spell.viewData.iconColor !== undefined) {
            obj.find('.svg').addClass(spell.viewData.iconColor);
        }
        MageS.Game.spellbook.addSpellDescription(spell, obj);
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
        var id = spellEl.data('id');
        $('.spell-tooltip.id-' + id + ' .cooldown')
            .find('.active').show().find('.value')
            .html(spellEl.data('cooldown-mark') - this.game.turn);
    };

    this.removeCooldown = function(spellEl) {
        info('removing cooldown');
        spellEl.removeClass('cooldown');
        var id = spellEl.data('id');
        $('.spell-tooltip.id-' + id + ' .cooldown .active').hide();
    };

    this.stepCooldown = function(spellEl) {
        var id = spellEl.data('id');
        $('.spell-tooltip.id-' + id + ' .cooldown .active .value')
            .html(spellEl.data('cooldown-mark') - this.game.turn);
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
    this.turnOffFilters = function () {
        $('.spell-filter.active').removeClass('active');
        $('.spellBook .spell.filtered-out').removeClass('filtered-out');
    };
    this.filterAllWithValueLessThen = function(value) {
        $('.spellBook .spell').each(function() {
            if (!$(this).hasClass('filtered-out') && parseInt($(this).find('.value').html()) < value) {
                $(this).addClass('filtered-out');
            }
        })
    };

    this.checkForActiveSpells = function(spell) {
        var activeSpell = $('.spell.active');
        if (activeSpell.length) {
            this.turnOffPatterns();
            this.removePermanentTooltip();
            this.turnOffActiveSpell();
            if (spell && activeSpell.data('id') == spell.data('id')) {
                return false;
            }
        }
        return true;
    };

    this.turnOffActiveSpell = function() {
        $('.spell.active').removeClass('active');
    };

    this.showPattern = function (spell, pattern) {
        if (this.game.actionInProcess == true) {
            info('Can`t show pattern while action is in process');
            return;
        }
        if (!this.checkForActiveSpells(spell)) {
            return;
        }
        this.activateSpellTooltipPermanent(spell);
        spell.addClass('active');
        $('#move-control-field').addClass('disable');
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
        $('#move-control-field').removeClass('disable');
        this.deleteHiddenDescription();
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
        var dataString = '{"id":"' + spell.data('id') + '","d":"' + d + '","x":"' + x + '","y":"' + y + '"}';
        this.castSpellStart(spell, dataString);
    };

    this.castSpellStart = function(spellEl, dataString) {
        this.removePermanentTooltip();
        MageS.Game.action('spell', dataString);
        var spellName = spellEl.data('spell');
        this.game.spells.startCast(spellName);
    };

    this.showEnemyTargets = function(spell) {
        if (!this.checkForActiveSpells(spell)) {
            return;
        }
        this.activateSpellTooltipPermanent(spell);
        spell.addClass('active');
        $('#move-control-field').addClass('disable');

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

        var rendered = Mustache.render(temaplate, {'id': data.id, 'name': data.viewData.name,
            'description' : data.viewData.description, 'cooldown': data.config.cooldown
        });
        var obj = $(rendered);
        if (data.viewData.noTargetSpell !== undefined) {
            obj.addClass('noTargetSpell');
        }
        $('.tooltip-spell-area').append(obj);
        if (this.game.device == 'pc') {
            spell.on({
                'mouseenter': function () {
                    var id = $(this).data('id');
                    $('.tooltip-spell-area .spell-tooltip.id-' + id).addClass('hover');
                },
                'mouseleave': function () {
                    var id = $(this).data('id');
                    $('.tooltip-spell-area .spell-tooltip.id-' + id).removeClass('hover');
                }
            });
        }
    };
    this.createHiddenDescription = function(spellEl) {
        var id = spellEl.data('id');
        var tooltip = $('.tooltip-spell-area .spell-tooltip.id-' + id).clone();
        tooltip.height(this.game.chat.inventorySize + 'rem');
        $('#mobile-spell-info-container').show().append(tooltip);
    };
    this.deleteHiddenDescription = function() {
        $('#mobile-spell-info-container').hide().find('.spell-tooltip').remove();
        if ($('#mobile-spell-info-container').hasClass('active')) {
            this.toggleHiddenDescription();
        }
    };
    this.toggleHiddenDescription = function() {
        if ($('#mobile-spell-info-container').hasClass('active')) {
            $('#mobile-spell-info-container').removeClass('active').animate({
                'margin-left': - this.game.mageInventorySize + ((parseInt($('.right-panel').width()) * 15) / (this.game.rem * 100)) + 'rem'
            }, {'easing': 'easeOutElastic'});
        } else if (!$('#mobile-spell-info-container').hasClass('active')) {
            $('#mobile-spell-info-container').addClass('active').animate({
                'margin-left': '0'
            }, {'easing': 'easeOutElastic'});
        }
    };
    this.activateSpellTooltipPermanent = function(spellEl) {
        var id = spellEl.data('id');
        $('.tooltip-spell-area .spell-tooltip.id-' + id).addClass('click');
        var spellType = spellEl.data('spell-type');
        switch (spellType) {
            case 'noTargetSpell':

                break;
            case 'directTargetSpell':
            case 'pattern':

                break;
        }
    };
    this.removePermanentTooltip = function() {
        $('.spell-tooltip.click').removeClass('click');
    };
    this.addCastToDescription = function(spellEl) {
        var newCastButton = spellEl.find('.svg').clone().addClass('cast-button');
        if (spellEl.hasClass('cooldown')) {
            newCastButton.find('path').css({'fill': '#ccc'});
        } else {
            newCastButton.on('click', function (e) {
                e.stopPropagation();
                MageS.Game.spellbook.spellClick($('.spellBook .spell.active'));
            });
        }
        $('#mobile-spell-info-container .spell-tooltip').prepend(newCastButton);
    };

    this.panMobileSpellDescriptionRight = function (event) {
        if ($('#mobile-spell-info-container').hasClass('active')) {
            return;
        }
        $('#mobile-spell-info-container').css({
            'margin-left': - this.game.mageInventorySize + (1.5 * this.game.cellSize) + (event.distance / this.game.rem) + 'rem'
        });
    };
    this.panMobileSpellDescriptionLeft = function (event) {
        if (!$('#mobile-spell-info-container').hasClass('active')) {
            return;
        }
        $('#mobile-spell-info-container').css({
            'margin-left': - (event.distance / this.game.rem) + 'rem'
        });
    };

    this.toggleSpellbook = function() {
        if ($('.spells-col').hasClass('active')) {
            //this.hideSpellbook();
        } else {
            this.showSpellbook();
        }
    };
    this.showSpellbook = function() {
        if (this.game.device !== 'pc') {
            this.game.inventory.hideInventory();
            $('.spells-col').addClass('active').fadeIn();
            $('.toggle-spellbook').addClass('active');
            this.game.chat.hideChat();
        }
    };
    this.hideSpellbook = function() {
        $('.spells-col').hide().removeClass('active');
        $('.toggle-spellbook').removeClass('active');
        this.removePermanentTooltip();
        this.turnOffPatterns();
    };



};

