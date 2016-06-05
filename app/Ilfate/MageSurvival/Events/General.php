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
    
    

}