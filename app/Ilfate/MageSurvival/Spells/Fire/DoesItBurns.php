<?php namespace Ilfate\MageSurvival\Spells\Fire;

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
class DoesItBurns extends Fire
{
    protected $availablePatterns = [];

    protected $defaultCooldownMin = 4;
    protected $defaultCooldownMax = 6;

    public function setUsages()
    {
        $this->config['usages'] = 5;
    }

    protected function spellEffect($data)
    {
        $damage = $this->mage->getDamage(2, Spell::ENERGY_SOURCE_FIRE);
        foreach($this->targets as $target) {
            /**
             * @var Unit $target
             */
            $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT, Spell::ENERGY_SOURCE_FIRE);
            if ($target->getFlag(Unit::FLAG_BURN)) {
                $target->damage($damage + 1, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT, Spell::ENERGY_SOURCE_FIRE);
            }
        }
        return true;
    }
}