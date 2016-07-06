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
namespace Ilfate\MageSurvival\Spells\Air;

use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Spell;
use Ilfate\MageSurvival\Spells\Air;
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
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
class NoMoreAirForYou extends Air
{
    protected $defaultCooldownMin = 1;
    protected $defaultCooldownMax = 3;

    protected function spellEffect($data)
    {
        $target = $this->targets[0];
        $damage = $this->mage->getDamage(2, Spell::ENERGY_SOURCE_AIR);
        $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT, Spell::ENERGY_SOURCE_AIR);
        return true;
    }
}