/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Spellcraft = function (game) {
    this.game = game;
    this.craftingIsInProgress = false;
    this.spellCraftProcess = {};
    this.skweezing = {};
    this.craftAnimationsInProcess = 0;
    this.animationDestination = {};
    this.isSpellBlenderAllowed = true;
    this.isBlenderActive = false;
    this.blenderSchool = 0;
    
    this.init = function() {
        $('.craft-demo-zone .blend-spells-show-button').on('click', function(){ MageS.Game.spellcraft.showBlender()})
    }

    this.spellCrafted = function(data) {
        this.spellCraftProcess = [];
        //this.endSpellCraftAnimations();

        //this.game.spellbook.showSpellbook();

    };

    this.startSpellCraftAnimations = function () {
        // this.craftAnimationsInProcess = true;
        $('.craft-spell-overlay').hide();
        $('.craft-spell-overlay-blender').hide();
        $('.confirm-create-spell').removeClass('active');
        $('.helper-spell-craft-step-1, .helper-spell-craft-step-2, .spell-craft-info').remove();
        if (this.isBlenderActive) {
            this.hideBlender();
        }

        this.spellCraftProcess = {};

        $('.craft-demo-zone').removeClass('active');
        //     .animate({
        //     'opacity':0
        // }, {duration:1000, complete:function() {
        //     $(this);
        // }});
        this.game.spellbook.showSpellbook();
        //if (this.game.device == 'mobile') {
            //var destinationEl = $('.interface-switch-panel .toggle-spellbook');
            $('.spellBook .spells').append('<div class="spell empty-div"></div>');
            var destinationEl = $('.spellBook .empty-div');
            if (destinationEl.length < 1) {
                destinationEl = $('.spellBook');
            }
            this.animationDestination.top = destinationEl.offset().top;
            this.animationDestination.left = destinationEl.offset().left;
            $('.spellBook .empty-div').remove();


            //var pageHeight =  $(document).height();
            //var pageWidth =  $(document).width();
            var destinations = [
                [0, -50],
                [0, -100],
                [50, -100],
                [-50, -100],
            ];
            for (var i = 0; i < 4; i++) {
                var animationEl = $('.craft-animation-item.n-' + i);
                if (animationEl.length < 1) { continue; }
                //this.craftAnimationsInProcess ++;
                var thisTop = parseInt(animationEl.css('top'));
                var thisLeft = parseInt(animationEl.css('left'));
                animationEl.animate({
                    'top': thisTop + destinations[i][1] + 'px', 'left': thisLeft + destinations[i][0] + 'px'
                }, {
                    duration: 300, queue: false, easing: 'easeInOutCirc', complete: function () {

                    }
                });
            }
        //}
        //$('.inventory .item.animation').animate({
        //    'opacity':0
        //});

    };
    this.endSpellCraftAnimations = function (data, stage) {
        for (var i = 0; i < 4; i++) {
            var animationEl = $('.craft-animation-item.n-' + i);
            if (animationEl.length < 1) { continue; }
            //this.craftAnimationsInProcess ++;
            animationEl.animate({'left': this.animationDestination.left + 'px'}, {duration: 1000, queue:false, easing:'easeInBack'});
            animationEl.animate({
                'top': this.animationDestination.top + 'px', opacity: 0.3
            }, {
                duration: 1000, easing: 'easeInOutSine', complete: function () {
                    $(this).remove();
                }
            });
        }
        setTimeout(function(){
            MageS.Game.spellcraft.craftingIsInProgress = false;
        }, 1200);
        $('.inventory,.spellBook').removeClass('craft');
        $('.inventory-shadow').animate({'opacity': 0}, {'duration': this.game.animationTime / 3,'complete':function(){
            $(this).hide();
        }});
        $('.chemical-animation').html('');
        $('.item-drop-zone').removeClass('filled');
        //$('.craft-animation-item').remove();
        this.game.inventory.turnOffFilters();
        this.game.spellbook.turnOffFilters();
        //this.game.endAction();
        MageS.Game.animations.singleAnimationFinished(stage);
    };

    this.cancelCrafting = function () {
        $('.inventory').removeClass('craft');
        MageS.Game.spellcraft.hideShadow();
        $('.item-drop-zone').removeClass('filled').removeClass('active');
        $('.craft-animation-item').remove();
        $('.craft-spell-overlay').hide();
        $('.confirm-create-spell').removeClass('active');
        $('.chemical-animation').html('');
        $('.helper-spell-craft-step-1, .helper-spell-craft-step-2, .spell-craft-info').remove();
        $('.craft-value.active').removeClass('active').html('');
        $('.craft-demo-zone').removeClass('active');
        if (this.isBlenderActive) {
            this.hideBlender();
        }

        this.spellCraftProcess = {};
        setTimeout(function(){
            MageS.Game.spellcraft.craftingIsInProgress = false;
        }, 300);

        this.game.inventory.turnOffFilters();
    };
    this.hideBlender = function() {
        this.isBlenderActive = false;
        this.blenderSchool = 0;
        this.game.spellbook.turnOffFilters();
        $('.craft-spell-overlay-blender').hide();
        $('.items-col').prepend($('.craft-demo-zone'));
        $('.spellBook').removeClass('craft');
    };


    this.showSpellCrafting = function() {
        if (this.craftingIsInProgress) {
            return;
        }
        if (this.game.device != 'pc') {
            this.game.inventory.showInventory();
        }
        this.game.spellbook.removePermanentTooltip();
        var itemsCount = 0;
        $('.inventory .item.type-ingredient .value').each(function() {
            itemsCount += 1;
        });
        if (itemsCount < 3) {
            this.game.chat.postMessage('You need more than 3 ingredients to create a spell');
            return;
        }
        if (this.game.admin.isEnabled) {
            return;
        }
        this.craftingIsInProgress = true;
        MageS.Game.hideMenu();
        $('.craft-demo-zone').addClass('active');//.css({opacity:0}).animate({'opacity': 1},{queue:false,duration:300});
        MageS.Game.spellcraft.showShadow(function() {
            MageS.Game.spellcraft.cancelCrafting();
        });
        $('.inventory').addClass('craft');
        MageS.Game.inventory.filterItems($('.items-filter.name-ingredient'));

        this.showSpellCraftHelperStep1();
        $('.craft-spell-overlay').show();
        if (this.isSpellBlenderAllowed) {
            setTimeout(function() {
                $('.craft-demo-zone .blend-spells-show-button').fadeIn();
            }, 600);
        }
    };
    
    this.showBlender = function () {
        this.isBlenderActive = true;
        this.game.spellbook.showSpellbook();
        this.game.spellbook.turnOffFilters();
        $('.spellBook').addClass('craft');
        $('.craft-demo-zone .blend-spells-show-button').fadeOut();
        $('.spells-col').prepend($('.craft-demo-zone'));
        // this.showSpellCraftHelperBlender();
        $('.craft-spell-overlay-blender').show();
        $('.craft-spell-overlay').hide();
        this.game.spellbook.filterAllWithValueLessThen(5);
    };

    this.blenderCheck = function(spellEl) {
        if (this.blenderSchool === 0) {
            this.blenderSchool = spellEl.data('school');
            this.game.spellbook.filterSpells($('.spell-filter.school-' + spellEl.data('school')));
            this.game.spellbook.filterAllWithValueLessThen(5);
        } else {
            if (spellEl.data('school') !== this.blenderSchool) {
                return false;
            }
        }
        var quantity = parseInt(spellEl.find('.value').html());
        if (quantity < 5) {
            return false;
        }
        return true;
    };

    this.showShadow = function(callback) {
        var shadow = $('.inventory-shadow');
        shadow.show().animate({'opacity': 0.8}, {'duration': MageS.Game.animationTime / 3})
        if (callback) {
            shadow.bind('click', callback);
        }

    };
    this.hideShadow = function() {
        var shadow = $('.inventory-shadow');
        shadow.animate({'opacity': 0}, {'duration': MageS.Game.animationTime / 3,'complete':function(){
            $(this).hide();
        }});
        shadow.unbind('click');
    };

    this.itemClick = function(itemObj) {
        if (itemObj.hasClass('type-catalyst')) {
            this.SpellCraftStep2(itemObj);
        } else if (itemObj.hasClass('type-ingredient')) {
            this.SpellCraftStep1(itemObj);
        } else if (itemObj.hasClass('spell') && this.isBlenderActive === true) {
            if (this.blenderCheck(itemObj)) {
                this.SpellCraftStep1(itemObj);
            }
        }
    };

    this.SpellCraftStep2 = function(catalystEl) {
        this.spellCraftProcess.catalyst = catalystEl;
        $('.helper-spell-craft-step-2').hide(200, function(){ $(this).remove(); });
        this.skweezing.stop();
        this.addItemToCrafting(catalystEl, true);
        setTimeout(function() {
            MageS.Game.spellcraft.createSpellAction();
        }, 1000);
    };

    this.SpellCraftStep1 = function(ingridientEl) {
        if (!this.spellCraftProcess['ingridients']) {
            this.spellCraftProcess['ingridients'] = [];
        }
        if (this.spellCraftProcess['ingridients'].length > 3) {
            return;
        }
        $('.craft-demo-zone .blend-spells-show-button').fadeOut();
        $('.helper-spell-craft-step-2 .helper-spell-craft-step-2-error').remove();
        this.spellCraftProcess['ingridients'].push(ingridientEl);
        var isLastItem = this.spellCraftProcess['ingridients'].length == 3;
        this.addItemToCrafting(ingridientEl, false);
        if (isLastItem) {
            if (this.isBlenderActive === true) {
                setTimeout(function() {
                    MageS.Game.spellcraft.createSpellAction();
                }, 1000);
            } else {
                $('.spell-craft-2').hide(200, function () { $(this).remove(); });
                $('.helper-spell-craft-step-2').hide(200, function () { $(this).remove(); });
                this.showCatalystStep();
            }
        } else {
            //ingridientEl.find('path').css({'fill': '#069E2D', transition: "2.0s"});
            var number = $('.select-mode-ingredients');
            number.html(3 - this.spellCraftProcess['ingridients'].length);
            number.css('color', '#FF8360').animate({'color' : '#FFF'});
        }
    };

    this.addItemToCrafting = function(ingridientEl, isLastItem) {
        var copyEl = ingridientEl.find('.svg').clone();
        copyEl.addClass('craft-animation-item');
        ingridientEl.before(copyEl);
        var offsetTop = ingridientEl.offset().top;
        var offsetLeft = ingridientEl.offset().left;
        $('.mage-survival').append(copyEl);

        copyEl.css({'top':offsetTop, 'left':offsetLeft});
        var i = 1;
        if (!isLastItem) {
            for (i = 1; i <= 3; i++) {
                var zoneEl = $('.zone-' + i);
                if (!zoneEl.hasClass('filled')) {
                    break;
                }
            }
        } else {
            var zoneEl = $('.chemical-animation');
            i = 0;
        }
        copyEl.addClass('n-' + i);
        var zoneSvg = zoneEl.find('.svg');
        var offsetTopZone = zoneSvg.offset().top / this.game.rem;
        var offsetLeftZone = zoneSvg.offset().left / this.game.rem;
        var cellSizeBuffer = 0.25 * this.game.cellSize;
        var cellSizeBufferX = 0;
        var cellSizeBufferY = 0;
        if (i > 1) {
            cellSizeBuffer = 0.4 * this.game.cellSize;
        }
        if (isLastItem) {
            cellSizeBuffer = 0;
            cellSizeBufferX = -0.5 * this.game.cellSize;
            cellSizeBufferY = 0.05 * this.game.cellSize;
        }
        MageS.Game.monimations.skweeze(copyEl);
        copyEl.animate({
            'top': offsetTopZone + cellSizeBuffer + cellSizeBufferY + 'rem'
        }, {duration:1000, queue:false, easing:'easeInOutCirc'});
        copyEl.animate({
            'left':offsetLeftZone + cellSizeBuffer + cellSizeBufferX + 'rem'
        }, {duration:1000, queue:false, easing:'easeOutBack', complete: function() {
              //
        }});
        zoneEl.addClass('filled');
        ingridientEl.addClass('filtered-out');

    };




    this.showSpellCraftHelperStep1 = function() {
        var temaplate = $('#template-helper-spell-craft-step-1').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate);
        var obj = $(rendered);
        $('.craft-spell-overlay').append(obj);
    };

    this.showCatalystStep = function () {
        var temaplate = $('#template-spell-craft-catalyst').html();
        Mustache.parse(temaplate);

        var rendered = Mustache.render(temaplate, {

        });
        var obj = $(rendered);

        MageS.Game.inventory.filterItems($('.items-filter.name-catalyst'));

        $('.craft-spell-overlay').append(obj);
        var createButton = $('.confirm-create-spell');
        createButton.addClass('active');
        this.game.monimations.blastInScale(createButton, 1.33);
        createButton.on('click', function(){
            MageS.Game.spellcraft.createSpellAction();
        });
        var svgEl = $('<div></div>').addClass('svg');
        $('.chemical-animation').append(svgEl);
        svgEl.svg({
            onLoad: function (svg) {
                svg.circle(0, 0, 1.2 * MageS.Game.rem,
                    {fill: 'none', stroke: '#fff',strokeWidth: 0.1 * MageS.Game.rem});
            }
        });
        var svg = svgEl.find('svg').width('1').height('1');
        this.skweezing = MageS.Game.monimations.skweezeSlow(svg);
    };

    this.createSpellAction = function()
    {
        var items = [];
        for(var i in this.spellCraftProcess.ingridients) {
            items.push(this.spellCraftProcess.ingridients[i].data('id'))
        }
        if (this.spellCraftProcess.catalyst !== undefined) {
            items.push(this.spellCraftProcess.catalyst.data('id'))
        }
        var isBlender = this.isBlenderActive;
        this.startSpellCraftAnimations();
        var data = '{"ingredients": ["' + items[0] + '","' + items[1] + '","' + items[2] ;
        if (items[3] !== undefined) {
            data += '","' + items[3]
        }
        data +=  '"]';
        if (isBlender === true) {
            data +=  ',"blender":true';
        }
        data +=  '}';
        this.game.action('craftSpell', data);
    };

    this.showSpellCraftInfo = function() {
        var temaplate = $('#template-spell-craft-info').html();
        Mustache.parse(temaplate);

        var stats = {
            //'usagesMin':0,
            //'usagesMax':0,
            //'spell':0,
            //'school':{},
            //'cooldown':{},
        };
        //var carrier = this.game.inventory.items[this.spellCraftProcess.carrier.data('id')];
        //var usagesArr = carrier.stats.usages.split('-');
        //stats.usagesMin = usagesArr[0];
        //stats.usagesMax = usagesArr[1];
        var items = [];
        for(var i in this.spellCraftProcess.ingridients) {
            items.push(this.game.inventory.items[this.spellCraftProcess.ingridients[i].data('id')]);
        }
        for (var i in items) {
            var item = items[i];
            //if (item.stats.spell !== undefined) {
            //    stats.spell += parseInt(item.stats.spell);
            //}
            //if (item.stats.school !== undefined) {
            //    stats.isSchool = true;
            //    for(var schoolName in item.stats.school) {
            //        if (stats.school[schoolName] === undefined) {
            //            stats.school[schoolName] = 0;
            //        }
            //        stats.school[schoolName] += item.stats.school[schoolName];
            //    }
            //}
            //if (item.stats.cooldown !== undefined) {
            //    stats.isCooldown = true;
            //    if (stats.cooldown.min === undefined) {
            //        stats.cooldown.min = 0;
            //    }
            //    if (stats.cooldown.max === undefined) {
            //        stats.cooldown.max = 0;
            //    }
            //    stats.cooldown.min += parseInt(item.stats.cooldown.min);
            //    stats.cooldown.max += parseInt(item.stats.cooldown.max);
            //}

        }
        //if (stats.isSchool !== undefined) {
        //    var schools = [];
        //    for(var schoolName in stats.school) {
        //        schools.push({'name':schoolName,'value':stats.school[schoolName]});
        //    }
        //    stats.school = schools;
        //}
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
        craftItemsEl.css('width', itemsNum * this.game.itemSize + 'rem');
        $('.craft-spell-overlay').append(obj);
        var height = this.game.mageInventorySize;
        if (this.game.device == 'mobile') {
            height = this.game.mageMobileInventorySize;
        }
        obj.animate({'height': height + 'rem'}, {'duration':this.game.animationTime, 'easing':'easeInCirc'});
        $('.confirm-create-spell').on('click', function(){
            MageS.Game.spellcraft.createSpellAction();
        });
    };
};

