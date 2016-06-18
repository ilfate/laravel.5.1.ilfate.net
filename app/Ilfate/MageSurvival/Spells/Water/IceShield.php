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
use Ilfate\MageSurvival\Spell;
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
class IceShield extends Water
{
    protected function spellEffect($data)
    {
        $armor = $this->mage->getDamage(5, Spell::ENERGY_SOURCE_AIR);
        $this->mage->armor($armor, $this->getNormalCastStage());

        Event::create(Event::EVENT_MAGE_AFTER_ATTACKED_BY_UNIT, ['times' => 3], 'Water:iceShield');
        return true;
    }
}