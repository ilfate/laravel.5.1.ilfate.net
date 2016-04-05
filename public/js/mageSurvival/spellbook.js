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
            isCastAllowed = true;
        } else if(this.game.device == 'tablet') {
            isCastAllowed = true;
        }
        switch (spellType) {
            case 'noTargetSpell':
                if (isCastAllowed) {
                    this.castSpellStart(spellEl, '{"id":"' + spellEl.data('id') + '"}');
                    spellEl.removeClass('active');
                } else {
                    this.turnOffPatterns();
                    isAddCastToDescription = true;
                    spellEl.addClass('active');
                }
                break;
            case 'directTargetSpell':
                switch (targetType) {
                    case 'enemy':
                        //find all enemies/
                        // hgihtlight them
                        MageS.Game.spellbook.showEnemyTargets(spellEl);
                        break;
                }
                break;
            case 'pattern':
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
                this.game.monimations.scaleIn(obj);
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
            obj.find('.svg').addClass(spell.viewData.iconColor);
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
                var id = $(this).data('id');
                $('.spell-tooltip.id-' + id+ ' .cooldown').hide();
            } else {
                MageS.Game.spellbook.stepCooldown($(this));
            }
        });
    };

    this.addCooldown = function(spellEl) {
        spellEl.addClass('cooldown');
        var id = spellEl.data('id');
        $('.spell-tooltip.id-' + id + ' .cooldown')
            .show()
            .find('.value')
            .html(spellEl.data('cooldown-mark') - this.game.turn);
    };

    this.removeCooldown = function(spellEl) {
        info('removing cooldown');
        spellEl.removeClass('cooldown');
    };

    this.stepCooldown = function(spellEl) {
        var id = spellEl.data('id');
        $('.spell-tooltip.id-' + id + ' .cooldown .value')
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
        MageS.Game.action('spell', dataString);
        var spellName = spellEl.data('spell');
        this.game.spells.startCast(spellName);
    };

    this.showEnemyTargets = function(spell) {
        if (!this.checkForActiveSpells(spell)) {
            return;
        }
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
    this.createHiddenDescription = function(spellEl) {
        var id = spellEl.data('id');
        var tooltip = $('.tooltip-spell-area .spell-tooltip.id-' + id).clone();
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
                'margin-left': - this.game.mageInventorySize + (1.5 * this.game.cellSize) + 'px'
            }, {'easing': 'easeOutElastic'});
        } else if (!$('#mobile-spell-info-container').hasClass('active')) {
            $('#mobile-spell-info-container').addClass('active').animate({
                'margin-left': '0px'
            }, {'easing': 'easeOutElastic'});
        }
    };
    this.addCastToDescription = function(spellEl) {
        var newCastButton = spellEl.find('.svg').clone().addClass('cast-button');
        newCastButton.on('click', function(e) {
            e.stopPropagation();
            MageS.Game.spellbook.spellClick($('.spellBook .spell.active'));
        });
        $('#mobile-spell-info-container .spell-tooltip').prepend(newCastButton);
    };

    this.panMobileSpellDescriptionRight = function (event) {
        if ($('#mobile-spell-info-container').hasClass('active')) {
            return;
        }
        $('#mobile-spell-info-container').css({
            'margin-left': - this.game.mageInventorySize + (1.5 * this.game.cellSize) + event.distance + 'px'
        });
    };
    this.panMobileSpellDescriptionLeft = function (event) {
        if (!$('#mobile-spell-info-container').hasClass('active')) {
            return;
        }
        $('#mobile-spell-info-container').css({
            'margin-left': - event.distance + 'px'
        });
    };

    this.toggleSpellbook = function() {
        if ($('.spells-col').hasClass('active')) {
            this.hideSpellbook();
        } else {
            this.showSpellbook();
        }
    };
    this.showSpellbook = function() {
        if (this.game.device !== 'pc') {
            this.game.inventory.hideInventory();
            $('.spells-col').addClass('active').fadeIn();
        }
    };
    this.hideSpellbook = function() {
        $('.spells-col').hide().removeClass('active');
        this.turnOffPatterns();
    };



};

