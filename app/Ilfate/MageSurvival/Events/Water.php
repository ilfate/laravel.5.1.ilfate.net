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

use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
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
class Water extends Event
{
    public static function iceCrown($actionData) {
        if ($actionData['value'] > 0) {
            $actionData['value'] -= 1;
        }
        return $actionData;
    }
    public static function iceSlide($actionData) {
        $newX = $x = $actionData['x'];
        $newY = $y = $actionData['y'];
        $world = GameBuilder::getGame()->getWorld();
        for ($i = 0; $i < 5; $i++) {
            switch ($actionData['d']) {
                case 0 : $newY--; break;
                case 1 : $newX++; break;
                case 2 : $newY++; break;
                case 3 : $newX--; break;
            }
            if ($world->isPassable($newX, $newY)) {
                $x = $newX;
                $y = $newY;
            } else {
                break;
            }
        }
        $actionData['x'] = $x;
        $actionData['y'] = $y;
        $actionData['isUpdated'] = true;
        

        return $actionData;
    }
    public static function Freeze($actionData)
    {
        $actionData['no-move']   = true;
        $actionData['skip-turn'] = true;
        return $actionData;
    }
    public static function RemoveFreeze($actionData) {
        /**
         * @var Unit $unit
         */
        $unit = $actionData[Event::KEY_OWNER];
        $unit->removeFlag('frozen');
        GameBuilder::getGame()->addAnimationEvent(Game::EVENT_NAME_UNIT_REMOVE_STATUS, [
            'id' => $unit->getId(), 'flag' => 'frozen'
        ], Game::ANIMATION_STAGE_UNIT_ACTION_2);
        return $actionData;

    }
    public static function iceShield($actionData) {
        /**
         * @var Unit $target
         */
        $target = $actionData['attacker'];
        $target->freeze(3, Game::ANIMATION_STAGE_UNIT_ACTION_3);

        return $actionData;
    }
}