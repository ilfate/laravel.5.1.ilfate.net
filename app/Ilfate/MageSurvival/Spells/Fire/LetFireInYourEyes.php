<?php namespace Ilfate\MageSurvival\Spells\Fire;

use Ilfate\MageSurvival\Event;
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
class LetFireInYourEyes extends Fire
{

    protected $defaultCooldownMin = 3;
    protected $defaultCooldownMax = 5;

    protected function spellEffect($data)
    {
        foreach($this->targets as $target) {
            /**
             * @var Unit $target
             */
            Event::create(
                Event::EVENT_UNIT_AFTER_TURN, [
                Event::KEY_TIMES => 3,
                Event::KEY_OWNER => $target,
                Event::KEY_ON_COMPLETE => 'Fire:RemoveBurn'
            ],
                'Fire:Burn');
            $target->addFlag(Unit::FLAG_BURN);
            $damage = $this->mage->getDamage(2, Spell::ENERGY_SOURCE_FIRE);
            $target->damage($damage, $this->getNormalCastStage(), Spell::ENERGY_SOURCE_FIRE);
            
        }
        return true;
    }
}