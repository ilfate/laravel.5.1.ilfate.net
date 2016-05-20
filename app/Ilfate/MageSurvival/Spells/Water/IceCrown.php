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
namespace Ilfate\MageSurvival\Spells\Water;

use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Spells\Water;

/**
 * TODO: Short description.
 * TODO: Long description here.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
class IceCrown extends Water
{
    protected $defaultCooldownMin = 3;
    protected $defaultCooldownMax = 6;
    
    protected $availablePatterns = [4];

    protected function spellEffect($data)
    {
        Event::create(Event::EVENT_MAGE_BEFORE_GET_DAMAGE, ['times' => 3], 'Water:iceCrown');
        return true;
    }
}