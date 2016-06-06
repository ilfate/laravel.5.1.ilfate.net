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
class Objects extends Event
{
    public static function openDoor($actionData, $eventData) {

        $door = GameBuilder::getGame()->getWorld()->getObject($eventData['doorX'], $eventData['doorY']);
        $door->openDoor();
        $door->update();

        return $actionData;
    }

    public static function createLoot($actionData, $eventData)
    {
        $unit = $actionData['owner'];
        $x = $unit->getX();
        $y = $unit->getY();
        $world = GameBuilder::getWorld();
        $objectInPlace = $world->getObject($x, $y);
        if ($objectInPlace && empty($eventData['isForced'])) {
            return $actionData;
        } else if ($objectInPlace) {
            $objectInPlace->delete($actionData['stage']);
        }
        $object = $world->addObject($eventData['loot'], $x, $y);
        GameBuilder::animateEvent(Game::EVENT_NAME_ADD_OBJECT,
            ['object' => $object->exportForView()],
            $actionData['stage']);
        return $actionData;
    }

}