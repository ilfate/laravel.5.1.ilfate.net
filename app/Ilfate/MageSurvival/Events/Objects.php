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
        $door->open();
        $door->update();

        return $actionData;
    }

}