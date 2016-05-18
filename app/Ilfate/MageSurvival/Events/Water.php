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
    public static function iceCrown($data) {
        if ($data['value'] > 0) {
            $data['value'] -= 1;
        }
        return $data;
    }
    public static function iceSlide($data) {
        $newX = $x = $data['x'];
        $newY = $y = $data['y'];
        $world = GameBuilder::getGame()->getWorld();
        for ($i = 0; $i < 5; $i++) {
            switch ($data['d']) {
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
        $data['x'] = $x;
        $data['y'] = $y;

        return $data;
    }
    public static function Freeze($data) {
        $data['no-move'] = true;
        $data['skip-turn'] = true;
        return $data;
    }
    public static function RemoveFreeze($data) {
        /**
         * @var Unit $unit
         */
        $unit = $data[Event::KEY_OWNER];
        $unit->removeFlag('frozen');
        GameBuilder::getGame()->addAnimationEvent(Game::EVENT_NAME_UNIT_REMOVE_STATUS, [
            'id' => $unit->getId(), 'flag' => 'frozen'
        ], Game::ANIMATION_STAGE_UNIT_ACTION_2);
        return $data;
    }
}