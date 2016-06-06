/**
 * Created by Ilya Rubinchik (ilfate) on 08/04/16.
 */




MageS.Worlds = function (game) {
    this.game = game;

    this.worlds = {
        'Tutorial' : {
            'f1' : {'icon':'tile-wood-plank'},
            'f2' : {'icon':'tile-wood-plank', 'd' : 1},
            'f3' : {'icon':'tile-wood-plank', 'd' : 2},
            'f4' : {'icon':'tile-wood-plank', 'd' : 3},
            's' : {'icon':'icon-rock', 'icon-color': 'color-grey'},
            'w1' : {'icon':'icon-brick-wall', 'icon-color': 'color-grey'},
            'w2' : {'icon':'icon-brick-wall', 'icon-color': 'color-grey-darker'},
            'w3' : {'icon':'icon-brick-wall', 'icon-color': 'color-grey-lighter'},
            'w4' : {'icon':'icon-brick-wall-damaged', 'icon-color': 'color-grey'},
            'w5' : {'icon':'icon-brick-wall-damaged', 'icon-color': 'color-grey-darker'},
            'w6' : {'icon':'icon-brick-wall-damaged', 'icon-color': 'color-grey-lighter'},
        },
        'WitchForest' : {
            's' : {'icon':'icon-rock', 'icon-color': 'color-grey'},
            's1' : {'icon':'icon-rock', 'icon-color': 'color-grey'},
            'f2' : {'icon':'icon-grass', 'icon-color': 'color-yellow'},
            't0' : {'icon':'icon-forest', 'icon-color': 'color-green-darker', 'icon2' : {
                'icon':'icon-forest-base', 'icon-color':'color-brown'
            }},
            'tf' : {'icon':'icon-forest', 'icon-color': 'color-green', 'icon2' : {
                'icon':'icon-forest-base', 'icon-color':'color-brown'
            }},
            'tF' : {'icon':'icon-forest', 'icon-color': 'color-green-darkest', 'icon2' : {
                'icon':'icon-forest-base', 'icon-color':'color-brown'
            }},
            't1' : {'icon':'icon-pine-tree', 'icon-color': 'color-green-darker', 'icon2' : {
                'icon':'icon-pine-tree-base', 'icon-color':'color-brown'
            }},
            't2' : {'icon':'icon-pine-tree', 'icon-color': 'color-green-darkest'},
            't3' : {'icon':'icon-pine-tree', 'icon-color': 'color-green'},
            't4' : {'icon':'icon-tree-oak', 'icon-color': 'color-green-darker', 'icon2':{
                'icon':'icon-tree-oak-base', 'icon-color':'color-brown'
            }},
            'w1' : {'icon':'icon-brick-wall', 'icon-color': 'color-brown'},
            'w2' : {'icon':'icon-obelisk', 'icon-color': 'color-clay'},
            'r2' : {'icon':'icon-footprints', 'icon-color': 'color-grey-darkest'},
            'r3' : {'icon':'icon-footprints-2', 'icon-color': 'color-grey-darkest'},
            'r4' : {'icon':'icon-footprints-3', 'icon-color': 'color-grey-darkest'},
            'c' : {'icon':'icon-wall-texture-1', 'icon-color': 'color-clay'},
            'cc' : {'icon':'icon-wall-texture-2', 'icon-color': 'color-clay'},
            'cC' : {'icon':'icon-wall-texture-3', 'icon-color': 'color-clay'},
        },
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
            this.addIcon(cellConfig, cellObj);
        }
        if (cellConfig['icon2'] !== undefined) {
            this.addIcon(cellConfig['icon2'], cellObj);
        }
    };

    this.addIcon = function(cellConfig, cellObj) {
        var icon = this.game.getIcon(cellConfig['icon']);
        var svgContainerEl = $('<div></div>').addClass('svg svg-cell').append($('<svg class="svg-icon" viewBox="0 0 512 512"></svg>'));
        if (cellConfig['d'] !== undefined) {
            var randAngle = [0,90,180,270];
            var angle = randAngle[cellConfig['d']];
            svgContainerEl.find('svg')[0].style.transform = 'rotate(' + angle + 'deg)';
        }
        svgContainerEl.find('svg').append(icon.clone());
        cellObj.append(svgContainerEl);
        if (cellConfig['icon-color'] !== undefined) {
            svgContainerEl.addClass(cellConfig['icon-color']);
        }
    }


};

