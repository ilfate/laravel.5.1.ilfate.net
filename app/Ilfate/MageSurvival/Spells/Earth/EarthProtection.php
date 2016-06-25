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
namespace Ilfate\MageSurvival\Spells\Earth;

use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Spells\Earth;

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
class EarthProtection extends Earth
{
    protected $defaultCooldownMin = 5;
    protected $defaultCooldownMax = 6;
    
    protected $availablePatterns = [];

    protected function spellEffect($data)
    {
        $this->mage->heal(5, $this->getNormalCastStage());
        $this->setNexStage();
        $this->mage->armor(3, $this->getNormalCastStage());
        Event::create(Event::EVENT_MAGE_BEFORE_GET_DAMAGE, [Event::KEY_TURNS => 1], 'General:noDamage');
        return true;
    }
}