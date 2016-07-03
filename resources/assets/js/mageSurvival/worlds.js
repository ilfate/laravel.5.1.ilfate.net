/**
 * Created by Ilya Rubinchik (ilfate) on 08/04/16.
 */




MageS.Worlds = function (game) {
    this.game = game;

    this.worlds = {
        'Tutorial' : {
            'f1' : {'image':'tile-wood-plank' },
            'f2' : {'image':'tile-wood-plank', 'd' : 1 },
            'f3' : {'image':'tile-wood-plank', 'd' : 2 },
            'f4' : {'image':'tile-wood-plank', 'd' : 3 },
            's' : {'image':'tile-wood-plank', 'icon':'icon-rock'},
            's1' : {'icon':'tile-column-grey', 'image':'tile-wood-plank'},
            'w1' : {'icon':'icon-brick-wall', 'icon-color': 'color-grey'},
            'w2' : {'icon':'icon-brick-wall', 'icon-color': 'color-grey-darker'},
            'w3' : {'icon':'icon-brick-wall', 'icon-color': 'color-grey-lighter'},
            'w4' : {'icon':'icon-brick-wall-damaged', 'icon-color': 'color-grey'},
            'w5' : {'icon':'icon-brick-wall-damaged', 'icon-color': 'color-grey-darker'},
            'w6' : {'icon':'icon-brick-wall-damaged', 'icon-color': 'color-grey-lighter'},
        },
        'WitchForest' : {
            's' : {'icon':'icon-rock', 'icon-color': 'color-grey'},
            's1' : {'image':'cave-rock-1'},
            's2' : {'image':'cave-rock-2'},
            's3' : {'image':'cave-rock-3'},
            's4' : {'image':'cave-rock-4'},
            // 'f2' : {'icon':'icon-grass', 'icon-color': 'color-yellow'},
            // 't0' : {'icon':'icon-forest', 'icon-color': 'color-green-darker', 'icon2' : {
            //     'icon':'icon-forest-base', 'icon-color':'color-brown'
            // }},
            // 't0' : {'image':'forest1'},
            // 'tf' : {'image':'forest1', d : 1},
            // 'Tf' : {'image':'forest1', d : 2},
            // 'tF' : {'image':'forest2'},
            // 'TF' : {'image':'forest2', d : 3},
            // 'T0' : {'image':'forest2', d : 1},
            // 'tf' : {'icon':'icon-forest', 'icon-color': 'color-green', 'icon2' : {
            //     'icon':'icon-forest-base', 'icon-color':'color-brown'
            // }},
            // 'tF' : {'icon':'icon-forest', 'icon-color': 'color-green-darkest', 'icon2' : {
            //     'icon':'icon-forest-base', 'icon-color':'color-brown'
            // }},
            'f1' : {'image':'grass1'},
            'f2' : {'image':'grass2'},
            'f5' : {'image':'grass4'},
            'f6' : {'image':'grass-flowers-1'},
            'f7' : {'image':'grass-flowers-2'},
            'f8' : {'image':'grass-flowers-3'},
            'f9' : {'image':'grass-decor-1'},
            'f0' : {'image':'grass-decor-2'},
            'F1' : {'image':'grass-decor-3'},
            'F2' : {'image':'grass-stump'},
            'F3' : {'image':'grass3'},

            'f3' : {'image':'grass-stump-burned'},

            't1' : {'image':'forest-tree'},
            't2' : {'image':'forest-tree2'},
            't3' : {'image':'forest-tree3'},
            't4' : {'image':'forest-tree4'},
            't5' : {'image':'forest-tree5'},
            't6' : {'image':'forest-tree6'},
            
            'c1' : {'image':'cave-floor-1'},
            'c2' : {'image':'cave-floor-2'},
            'c3' : {'image':'cave-floor-3'},
            'r2' : {'image':'cave-road'},
            // 't2' : {'icon':'icon-pine-tree', 'icon-color': 'color-green-darkest'},
            // 't3' : {'icon':'icon-pine-tree', 'icon-color': 'color-green'},
            // 't4' : {'icon':'icon-tree-oak', 'icon-color': 'color-green-darker', 'icon2':{
            //     'icon':'icon-tree-oak-base', 'icon-color':'color-brown'
            // }},
            'w1' : {'icon':'icon-brick-wall', 'icon-color': 'color-brown'},
            'w2' : {'icon':'icon-obelisk', 'icon-color': 'color-clay'},
            // 'r2' : {'icon':'icon-footprints', 'icon-color': 'color-grey-darkest'},
            'r3' : {'icon':'icon-footprints-2', 'icon-color': 'color-grey-darkest'},
            'r4' : {'icon':'icon-footprints-3', 'icon-color': 'color-grey-darkest'},
            'c' : {'image':'cave-wall-1'},
            'cc' : {'image':'cave-wall-2'},
            'cC' : {'image':'cave-wall-3'},
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
        if (cellConfig['image'] !== undefined) {
            this.addImage(cellConfig, cellObj);
        }
        if (cellConfig['icon'] !== undefined) {
            this.addIcon(cellConfig, cellObj);
        }
        if (cellConfig['icon2'] !== undefined) {
            this.addIcon(cellConfig['icon2'], cellObj);
        }
    };

    this.cellsChange = function(data, stage) {
        var cell = $('.battle-border .cell.x-' + data.targetX + '.y-' + data.targetY);
        var svgs = cell.find('.svg');
        if (svgs.length > 0) {
            svgs.remove();
        }
        var currentType = cell.data('class');
        var currentImage = cell.data('image');
        if (currentImage) {
            cell.removeClass(currentImage);
        }
        cell.removeClass(currentType).addClass(data.cell).data('class', data.cell);
        this.game.worlds.cell(this.game.worldType, data.cell, cell);
        MageS.Game.animations.singleAnimationFinished(stage);
    };

    this.addIcon = function(cellConfig, cellObj) {
        var icon = this.game.getIcon(cellConfig['icon']);
        var svgContainerEl = $('<div></div>').addClass('svg svg-cell').append($('<svg class="svg-icon" viewBox="0 0 512 512"></svg>'));
        if (cellConfig['d'] !== undefined) {
            var randAngle = [0, 90, 180, 270];
            var angle = randAngle[cellConfig['d']];
            svgContainerEl.find('svg')[0].style.transform = 'rotate(' + angle + 'deg)';
        }
        svgContainerEl.find('svg').append(icon.clone());
        cellObj.append(svgContainerEl);


        if (cellConfig['icon-color'] !== undefined) {
            svgContainerEl.addClass(cellConfig['icon-color']);
        }
    };

    this.addImage = function(cellConfig, cellObj) {
        cellObj.addClass('tile-image ' + cellConfig['image']).data('image', cellConfig['image']);
        if (cellConfig['d'] !== undefined) {
            var randAngle = [0, 90, 180, 270];
            var angle = randAngle[cellConfig['d']];
            cellObj[0].style.transform = 'rotate(' + angle + 'deg)';
        }
    };


};

