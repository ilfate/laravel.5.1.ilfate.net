<?php
/**
 * TODO: Package description.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 *
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
namespace Ilfate\MageSurvival\MapObjects;

use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Mage;
use Ilfate\MageSurvival\MapObject;
use Ilfate\MageSurvival\ChanceHelper;

/**
 * TODO: Short description.
 * TODO: Long description here.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 *
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
class Corpse extends MapObject
{

    protected $possibleItems = [304];

    public function getActions()
    {
        return [
            ['name' => 'Loot the corpse',
             'method' => 'open',
             'key' => 'E',
             'icon' => 'icon-carrion',
             'location' => 'actions'
            ]
        ];
    }

    public function open(Mage $mage)
    {
        $itemId = ChanceHelper::oneFromArray($this->possibleItems);
        GameBuilder::message('Congratulations! You found item :item', '', ['data' => ['item' => $itemId]]);
        $mage->addItem($itemId);
        $this->delete();
        return ['action' => 'itemsFound', 'data' => [$itemId]];
    }
}