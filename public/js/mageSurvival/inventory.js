/**
 * Created by Ilya Rubinchik (ilfate) on 06/11/15.
 */




MageS.Inventory = function (game) {
    this.game = game;

    this.buildItems = function() {
        $('.inventory .item').each(function() {
            var data = $(this).data('description');
            info(data);
            MageS.Game.inventory.addItemDescription(data, $(this));
        });
    };

    this.updateItems = function(items) {
        for(var type in items) {
            for(var id in items[type]) {
                var config = items[type][id];
                var typeEl = $('.items-tab.' + type);
                var existingEl = typeEl.find('.item.id-' + id)
                if (existingEl.length) {
                    //add item
                    var currentValue = parseInt(existingEl.html());
                    var newQuantity = currentValue + config.quantity;
                    if (newQuantity > 0) {
                        existingEl.html(newQuantity);
                    } else {
                        existingEl.remove();
                    }
                } else {
                    //create new item
                    if (!typeEl.length)
                    {
                        var tabId = 'items-tab-' + type;
                        $('.inventory .tab-content').append(
                            $('<div role="tabpanel" class="tab-pane items-tab"></div>').addClass(type).attr('id', tabId)
                        );
                        $('.inventory .nav.nav-tabs').append(
                            $('<li role="presentation"><a href="#' + tabId + '" aria-controls="' + tabId + '" role="tab" data-toggle="tab">' + type + '</a></li>')
                        );

                    }
                    var temaplate = $('#template-item').html();
                    Mustache.parse(temaplate);
                    var rendered = Mustache.render(temaplate, {
                        'id': id,
                        'item': config.image,
                        'name': config.name,
                        'quantity': config.quantity,
                    });
                    var obj = $(rendered);
                    $('.items-tab.' + type).append(obj);

                    this.addItemDescription(config, obj);

                }
            }
        }
    };

    this.addItemDescription = function(data, item) {
        var temaplate = $('#template-item-tooltip').html();
        Mustache.parse(temaplate);
        var rendered = Mustache.render(temaplate, {'id': data.id, 'name': data.name, 'stats' : data.stats, 'item': data.type});
        var obj = $(rendered);
        $('.tooltip-helper-area').append(obj);

        item.on({
            'mouseenter': function() {
                var id = $(this).data('id');
                $('.tooltip-helper-area .item-tooltip.id-' + id).show();
            },
            'mouseleave':function() {
                var id = $(this).data('id');
                $('.tooltip-helper-area .item-tooltip.id-' + id).hide();
            }
        });
    };

    this.addItems = function(game) {
    };
};

