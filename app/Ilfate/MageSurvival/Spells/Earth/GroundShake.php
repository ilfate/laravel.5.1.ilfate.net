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

use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\Spell;
use Ilfate\MageSurvival\Spells\Earth;
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
class GroundShake extends Earth
{
    protected $defaultCooldownMin = 1;
    protected $defaultCooldownMax = 3;
    
    protected $availablePatterns = [28];

    protected function spellEffect($data)
    {
        foreach($this->targets as $target) {
            /**
             * @var Unit $target
             */
            $damage = $this->mage->getDamage(1, Spell::ENERGY_SOURCE_EARTH);
            $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT, Spell::ENERGY_SOURCE_EARTH);
        }
        $this->changeCellsBySpellSource(
            $this->affectedCells,
            Spell::ENERGY_SOURCE_EARTH,
            Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT_2
        );
        return true;
    }
}