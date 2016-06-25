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
class SkyFist extends Air
{
    protected $defaultCooldownMin = 1;
    protected $defaultCooldownMax = 3;
    protected $availablePatterns = [20,21,22,23];

    protected function spellEffect($data)
    {
        $x = $this->pattern[0][0] + $this->mage->getX();
        $y = $this->pattern[0][1] + $this->mage->getY();
        $this->pushAllAround($x, $y, 1);
        if ($this->targets) {
            $target = $this->targets[0];
            $damage = $this->mage->getDamage(3, Spell::ENERGY_SOURCE_AIR);
            $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT_2, Spell::ENERGY_SOURCE_AIR);
        }
        if (ChanceHelper::chance(10)) {
            $this->mage->say('Talk to the hand', Game::ANIMATION_STAGE_MAGE_BEFORE_ACTION_SPEECH);
        }
        return true;
    }
}