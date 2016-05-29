<?php namespace Ilfate\MageSurvival\Spells\Fire;

use Ilfate\MageSurvival\Spell;
use Ilfate\MageSurvival\Spells\Fire;
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
class Fireball extends Fire
{
    protected $availablePatterns = [4];

    protected $defaultCooldownMin = 0;
    protected $defaultCooldownMax = 2;

    protected function spellEffect($data)
    {
        $damage = $this->mage->getDamage(1, Spell::ENERGY_SOURCE_FIRE);
        foreach($this->targets as $target) {
            /**
             * @var Unit $target
             */
            $target->damage($damage, $this->getNormalCastStage(), Spell::ENERGY_SOURCE_FIRE);
        }
        return true;
    }
}