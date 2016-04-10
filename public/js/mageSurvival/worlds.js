/**
 * Created by Ilya Rubinchik (ilfate) on 08/04/16.
 */




MageS.Worlds = function (game) {
    this.game = game;

    this.worlds = {
        'Tutorial' : {
            's' : {'icon':'icon-rock', 'icon-color': 'color-grey'}
        }
    };

    this.cell = function(world, cell, cellObj) {
        if (this.worlds[world] === undefined) {
            return false;
        }
        if (this.worlds[world][cell] === undefined) {
            return false;
        }
        var cellConfig = this.worlds[world][cell];
        if (cellConfig['icon'] !== undefined) {
            var icon = $(this.game.svg).find('#' + cellConfig['icon'] + ' path');
            var svgContainerEl = $('<div></div>').addClass('svg svg-cell').append($('<svg class="svg-icon" viewBox="0 0 512 512"></svg>'));

            svgContainerEl.find('svg').append(icon.clone());
            cellObj.append(svgContainerEl);
            if (cellConfig['icon-color'] !== undefined) {
                svgContainerEl.addClass(cellConfig['icon-color']);
            }
        }
    }


};

