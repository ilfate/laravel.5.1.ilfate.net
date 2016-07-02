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
class RollingStones extends Earth
{
    protected $defaultCooldownMin = 5;
    protected $defaultCooldownMax = 7;
    
    protected $availablePatterns = [29];

    protected function spellEffect($data)
    {
        $damage = $this->mage->getDamage(3, Spell::ENERGY_SOURCE_EARTH);
        foreach($this->targets as $target) {
            /**
             * @var Unit $target
             */
            $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT, Spell::ENERGY_SOURCE_EARTH);
        }
        return true;
    }
}