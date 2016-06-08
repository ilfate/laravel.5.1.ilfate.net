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
class General extends Event
{
    public static function addUserFlag($actionData, $eventData) 
    {
        GameBuilder::getGame()->setUserFlag($eventData['flag'], $eventData['value']);
        return $actionData;
    }

    public static function mageSay($actionData, $eventData)
    {
        GameBuilder::getGame()->getMage()->say($eventData['text'], $eventData['stage']);
        return $actionData;
    }

    public static function say($actionData, $eventData)
    {
        $actionData[Event::KEY_OWNER]->say($eventData['text'], $eventData['stage']);
        return $actionData;
    }
    
    

}