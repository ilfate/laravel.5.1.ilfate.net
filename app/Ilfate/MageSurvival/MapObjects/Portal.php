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

use Ilfate\MageSurvival\Game;
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
class Portal extends MapObject
{


    public function getActions()
    {
        return [
            ['name' => 'Enter portal',
             'method' => 'enter',
             'key' => 'E',
             'icon' => 'icon-magic-portal',
             'location' => 'actions'
            ]
        ];
    }

    public function enter(Mage $mage)
    {
        $mage->leaveWorld();
        GameBuilder::getGame()->addAnimationEvent(Game::EVENT_NAME_MAGE_USE_PORTAL, [
        ], Game::ANIMATION_STAGE_MAGE_ACTION);
//        $itemId = ChanceHelper::oneFromArray($this->possibleItems);
//        GameBuilder::message('Congratulations! You found item :item', '', ['data' => ['item' => $itemId]]);
//        $mage->addItem($itemId);
//        $this->delete();
//        return ['action' => 'itemsFound', 'data' => [$itemId]];
    }
}