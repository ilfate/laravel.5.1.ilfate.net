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
class ExplodingBees extends Fire
{

    protected $defaultCooldownMin = 4;
    protected $defaultCooldownMax = 7;

    protected function spellEffect($data)
    {
        foreach($this->targets as $target) {
            /**
             * @var Unit $target
             */
            $damage = $this->mage->getDamage(mt_rand(1, 4), Spell::ENERGY_SOURCE_FIRE);
            $target->damage($damage, $this->getNormalCastStage(), Spell::ENERGY_SOURCE_FIRE);
        }
        return true;
    }
}