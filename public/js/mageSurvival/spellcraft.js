/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Spellcraft = function (game) {
    this.game = game;
    this.craftingIsInProgress = false;
    this.spellCraftProcess = {};

    this.spellCrafted = function(data) {
        this.spellCraftProcess = [];
        this.endSpellCraftAnimations();

        this.game.spellbook.showSpellbook();

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
            $(this).hide();
        }});
        $('.helper-spell-craft-step-1, .helper-spell-craft-step-2, .spell-craft-info').remove();
        $('.craft-value.active').removeClass('active').html('');
        this.spellCraftProcess = {};
        this.craftingIsInProgress = false;
        this.game.inventory.turnOffFilters();
    };

    this.showSpellCrafting = function() {
        if (this.craftingIsInProgress) {
            return;
        }
        if (this.game.device != 'pc') {
            this.game.inventory.showInventory();
        }
        if (!$('.inventory .item.type-carrier').length) {
            this.game.postMessage('You have no Carrier to create a spell');
            return;
        }
        var itemsCount = 0;
        $('.inventory .item.type-ingredient .value').each(function() {
            itemsCount += parseInt($(this).html());
        });
        if (itemsCount < 3) {
            this.game.postMessage('You need more then 3 ingredients to create a spell');
            return;
        }
        this.craftingIsInProgress = true;
        $('.inventory-shadow').show().animate({'opacity': 0.8}, {'duration': this.game.animationTime / 3})
        $('.inventory').addClass('craft');
        MageS.Game.inventory.filterItems($('.items-filter.name-carrier'));

        this.showSpellCraftHelperStep1();
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
        MageS.Game.inventory.filterItems($('.items-filter.name-ingredient'));
        $('.helper-spell-craft-step-1').hide(200, function(){ $(this).remove(); });
        this.showSpellCraftHelperStep2();
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
            this.game.postMessage('You don`t have enough of that ingredient');
            var craftValue = ingridientEl.find('.craft-value');
            craftValue.css({'background-color':'#FF8360'}).animate({'background-color':'#529BCA'});
            return;
        }
        this.spellCraftProcess['ingridients'].push(ingridientEl);
        var isLastItem = this.spellCraftProcess['ingridients'].length == 3;
        this.addSpellCraftItemValue(ingridientEl, isLastItem);
        if (isLastItem) {
            $('.spell-craft-2').hide(200, function(){ $(this).remove(); });
            $('.helper-spell-craft-step-2').hide(200, function(){ $(this).remove(); });

            this.showSpellCraftInfo();

        } else {
            //ingridientEl.find('path').css({'fill': '#069E2D', transition: "2.0s"});
            var number = $('.select-mode-ingredients');
            number.html(3 - this.spellCraftProcess['ingridients'].length);
            number.css('color', '#FF8360').animate({'color' : '#FFF'});
        }
    };
    this.addSpellCraftItemValue = function(ingridientEl, isLastItem) {
        var craftValue = ingridientEl.find('.craft-value');
        craftValue.addClass('active');
        var currentNumber = parseInt(craftValue.html());
        if (!currentNumber) currentNumber = 0;
        currentNumber++;
        craftValue.html(currentNumber);
        if (!isLastItem) {
            craftValue.css({'background-color': '#069E2D'}).animate({'background-color': '#529BCA'});
        }
    };

    this.createSpellAction = function()
    {
        $('.spell-craft-info').remove();
        this.startSpellCraftAnimations();
        var carrier = this.spellCraftProcess.carrier.data('id');
        var items = [];
        for(var i in this.spellCraftProcess.ingridients) {
            items.push(this.spellCraftProcess.ingridients[i].data('id'))
        }
        this.cancelCrafting();
        var data = '{"carrier" : "' + carrier + '", "ingredients": ["' + items[0] + '","' + items[1] + '","' + items[2] + '"]}';
        this.game.action('craftSpell', data);
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

        var stats = {
            'usagesMin':0,
            'usagesMax':0,
            'spell':0,
            'school':{},
            'cooldown':{},
        };
        var carrier = this.game.inventory.items[this.spellCraftProcess.carrier.data('id')];
        var usagesArr = carrier.stats.usages.split('-');
        stats.usagesMin = usagesArr[0];
        stats.usagesMax = usagesArr[1];
        var items = [carrier];
        for(var i in this.spellCraftProcess.ingridients) {
            items.push(this.game.inventory.items[this.spellCraftProcess.ingridients[i].data('id')]);
        }
        for (var i in items) {
            var item = items[i];
            if (item.stats.spell !== undefined) {
                stats.spell += parseInt(item.stats.spell);
            }
            if (item.stats.school !== undefined) {
                stats.isSchool = true;
                for(var schoolName in item.stats.school) {
                    if (stats.school[schoolName] === undefined) {
                        stats.school[schoolName] = 0;
                    }
                    stats.school[schoolName] += item.stats.school[schoolName];
                }
            }
            if (item.stats.cooldown !== undefined) {
                stats.isCooldown = true;
                if (stats.cooldown.min === undefined) {
                    stats.cooldown.min = 0;
                }
                if (stats.cooldown.max === undefined) {
                    stats.cooldown.max = 0;
                }
                stats.cooldown.min += parseInt(item.stats.cooldown.min);
                stats.cooldown.max += parseInt(item.stats.cooldown.max);
            }

        }
        if (stats.isSchool !== undefined) {
            var schools = [];
            for(var schoolName in stats.school) {
                schools.push({'name':schoolName,'value':stats.school[schoolName]});
            }
            stats.school = schools;
        }
        var rendered = Mustache.render(temaplate, {
            'stats': stats
        });
        var obj = $(rendered);
        var craftItemsEl = obj.find('.craft-items');
        var itemsNum = 0
        for (var i in items) {
            // copy item
            if (craftItemsEl.find('.id-' + items[i].id).length > 0) continue;
            itemsNum++;
            var newItem = $('.inventory .item.id-' + items[i].id).clone();
            newItem.removeClass('filtered-out').find('.value').remove();
            newItem.find('.craft-value').css({'background-color': '#529BCA'});
            var craftValueEl = newItem.find('.craft-value');
            if (parseInt(craftValueEl.html()) == 1) {
                craftValueEl.remove();
            }
            craftItemsEl.append(newItem);
            this.game.inventory.bindItemTooltip(newItem);
            newItem.find('path').css({'fill': '#FFF'});
        }
        craftItemsEl.css('width', itemsNum * this.game.itemSize + 'px');
        $('.craft-spell-overlay').append(obj);
        var height = this.game.mageInventorySize;
        if (this.game.device == 'mobile') {
            height = this.game.mageMobileInventorySize;
        }
        obj.animate({'height': height + 'px'}, {'duration':this.game.animationTime, 'easing':'easeInCirc'});
        $('.confirm-create-spell').on('click', function(){
            MageS.Game.spellcraft.createSpellAction();
        });
    };
};

