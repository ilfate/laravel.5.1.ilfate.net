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
namespace Ilfate\MageSurvival\Events;

use Ilfate\MageSurvival\AliveCommon;
use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Spell;
use Ilfate\MageSurvival\Unit;

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
class Air extends Event
{
    public static function zip($actionData, $eventData) {
        $mage = GameBuilder::getGame()->getMage();
        $units = GameBuilder::getWorld()->getUnitsAround($mage->getX(), $mage->getY(), $eventData['range']);
        if ($units) {
            /**
             * @var AliveCommon $target
             */
            $target = ChanceHelper::oneFromArray($units);
            $damage = $mage->getDamage(1, Spell::ENERGY_SOURCE_AIR);
            $target->damage($damage, Game::ANIMATION_STAGE_TURN_END_EFFECTS_2, Spell::ENERGY_SOURCE_AIR);
            GameBuilder::getGame()->addAnimationEvent(Game::EVENT_NAME_EFFECT, [
                'spell' => 'QuardroLightning',
                'targets' => [[$target->getX() - $mage->getX(),$target->getY() - $mage->getY()]],
            ], Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT);
        }
        return $actionData;
    }
}