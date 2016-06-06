<?php namespace Ilfate\MageSurvival\Spells\Fire;

use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Game;
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
            $target->burn(3, $this->getNormalCastStage());
            $damage = $this->mage->getDamage(2, Spell::ENERGY_SOURCE_FIRE);
            $target->damage($damage, $this->getNormalCastStage(), Spell::ENERGY_SOURCE_FIRE);
            if (ChanceHelper::chance(10)) {
                $this->mage->say('Burn!!!', Game::ANIMATION_STAGE_MAGE_BEFORE_ACTION_SPEECH);
            }
        }
        return true;
    }
}