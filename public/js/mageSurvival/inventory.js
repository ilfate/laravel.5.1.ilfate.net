/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Inventory = function (game) {
    this.game = game;
    this.items = {};

    this.buildItems = function() {
        var template = $('#template-item').html();
        Mustache.parse(template);
        var itemsEl = $('.inventory .items');
        var items = itemsEl.data('items');
        for (var id in items) {
            var obj = this.renderItem(template, items[id]);
            itemsEl.append(obj);
            MageS.Game.inventory.addItemDescription(items[id], obj);
            MageS.Game.inventory.bindItem(obj);
        }
        itemsEl.data('items', '').attr('data-items', '');

        // build filters
        $('.items-filter').each(function(){
            $(this).on('click', function(){
                MageS.Game.inventory.filterItems($(this));
            })
        });
        $('.inventory .item').on('click', function() {
            //MageS.Game.monimations.skweeze($(this));
            //MageS.Game.monimations.spinItem($(this));
            //MageS.Game.monimations.scaleIn($(this));
            //$(this).animate({ textIndent2: 100 }, {
            //    step: function(now,fx) {
            //        info(now);
            //        $(this)[0].style.transform = 'scale(' + now/ 100 + ')';
            //    },
            //    duration:'slow', 'easing':'easeOutElastic'
            //});
        })
    };

    this.filterItems = function(filterEl) {
        var activeFilter = $('.items-filter.active');
        this.turnOffFilters();
        if (activeFilter.length && activeFilter.data('name') == filterEl.data('name')) {
            return;
        }
        filterEl.addClass('active');
        $('.inventory .item:not(.type-' + filterEl.data('name') + ')').addClass('filtered-out');
    };
    this.turnOffFilters = function () {
        $('.items-filter.active').removeClass('active');
        $('.inventory .item.filtered-out').removeClass('filtered-out');
    };

    this.renderItem = function(template, item) {
        var rendered = Mustache.render(template, {
            'id': item.id,
            //'class': item.class,
            'name': item.name,
            'type': item.type,
            'quantity': item.quantity,
        });
        var obj = $(rendered);
        var icon = $(this.game.svg).find('#' + item.icon + ' path');
        obj.find('svg').append(icon.clone());
        if (item.iconColor !== undefined) {
            obj.find('.svg').addClass(item.iconColor);
        }
        this.items[item.id] = item;
        return obj;
    };

    this.updateItems = function(items) {
        this.turnOffFilters();

        for(var id in items) {
            var config = items[id];
            var existingEl = $('.inventory .item.id-' + id);
            if (existingEl.length) {
                //add item
                var currentValue = parseInt(existingEl.find('.value').html());
                var newQuantity = currentValue + config.quantity;
                if (newQuantity > 0) {
                    existingEl.find('.value').html(newQuantity);
                    this.items[id] = config;
                } else {
                    existingEl.remove();
                }
                if (config.quantity > 0) {
                   // existingEl.
                   // this.game.monimations.spinItem(existingEl);
                    this.showInventory();
                    this.game.monimations.bounce(existingEl);
                    existingEl.find('.value').css({'background-color': '#069E2D', 'color':'#fff'}).animate({
                        'background-color': '#FCEBB6', 'color':'#5E412F'
                    }, {'duration': 2000});
                }
            } else {
                this.showInventory();
                //create new item
                var template = $('#template-item').html();
                Mustache.parse(template);
                var obj = this.renderItem(template, config);
                $('.inventory .items').append(obj);
                MageS.Game.monimations.scaleIn(obj);
                this.addItemDescription(config, obj);
                this.bindItem(obj);

            }
        }
    };

    this.addItemDescription = function(data, item) {
        var temaplate = $('#template-item-tooltip').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'id': data.id, 'name': data.name, 'stats' : data.stats, 'item': data.type});
        var obj = $(rendered);
        $('.tooltip-helper-area').append(obj);
        //if (this.game.device == 'pc') {
            this.bindItemTooltip(item);
        //}
    };

    this.bindItemTooltip = function(item) {
        item.on({
            'mouseenter': function () {
                var id = $(this).data('id');
                $('.tooltip-helper-area .item-tooltip.id-' + id).show();
            },
            'mouseleave': function () {
                var id = $(this).data('id');
                $('.tooltip-helper-area .item-tooltip.id-' + id).hide();
            }
        });
    };

    this.toggleInventory = function() {
        if ($('.items-col').hasClass('active')) {
            this.hideInventory();
        } else {
            this.showInventory();
        }
    };
    this.showInventory = function() {
        if (this.game.device !== 'pc') {
            this.game.spellbook.hideSpellbook();
            $('.items-col').addClass('active').fadeIn();
            this.game.spellbook.turnOffPatterns();
        }
    };
    this.hideInventory = function() {
        $('.items-col').hide().removeClass('active');
    };

    this.itemClick = function (itemObj) {
        if (MageS.Game.spellcraft.craftingIsInProgress) {
            MageS.Game.spellcraft.itemClick(itemObj);
        }
    };

    this.bindItem = function (item) {
        item.on('click', function() {
            MageS.Game.inventory.itemClick($(this));
        })
    };

    this.addItems = function(game) {
    };
};

