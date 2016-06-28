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
class General extends Event
{
    public static function protection($actionData, $eventData) 
    {
        if ($actionData['source'] === $eventData['source'] && $actionData['value'] > 0) {
            $actionData['value'] = 0;
        }
        return $actionData;
    }
    public static function addUserFlag($actionData, $eventData) 
    {
        if (!is_array($eventData['flag'])) {
            GameBuilder::getGame()->setUserFlag($eventData['flag'], $eventData['value']);
        } else {
            foreach ($eventData['flag'] as $flag) {
                GameBuilder::getGame()->setUserFlag($flag, $eventData['value']);
            }
        }
        return $actionData;
    }
    public static function removeWorldDataKey($actionData, $eventData) 
    {
        $world = GameBuilder::getWorld();
        $world->deleteDataKey($eventData['key']);
        return $actionData;
    }
    public static function noDamage($actionData, $eventData)
    {
        if ($actionData['value'] > 0) {
            $actionData['value'] = 0;
        }
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

    public static function tutorialStep($actionData, $eventData)
    {
        $world = GameBuilder::getGame()->getWorld();
        $world->addData('tutorialStep', $eventData['step']);
        if (!empty($eventData['message'])) {
            GameBuilder::getGame()->getMage()->say($eventData['message'], Game::ANIMATION_STAGE_MESSAGE_TIME);
        }
        $world->save();
        return $actionData;
    }
    
    

}