/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Inventory = function (game) {
    this.game = game;

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
            obj.addClass(item.iconColor);
        }
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
                } else {
                    existingEl.remove();
                }
            } else {
                //create new item
                var template = $('#template-item').html();
                Mustache.parse(template);
                var obj = this.renderItem(template, config);
                //var rendered = Mustache.render(temaplate, {
                //    'id': id,
                //    'class': config.class,
                //    'name': config.name,
                //    'type': config.type,
                //    'quantity': config.quantity,
                //});
                //var obj = $(rendered);
                $('.inventory .items').append(obj);

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
        if (this.game.device == 'pc') {
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
        }
    };

    this.itemClick = function (itemObj) {
        if (MageS.Game.spellbook.craftingIsInProgress) {
            MageS.Game.spellbook.itemClick(itemObj);
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

