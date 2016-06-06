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
        if ($this->targets) {
            $target = $this->targets[0];
            $damage = $this->mage->getDamage(3, Spell::ENERGY_SOURCE_AIR);
            $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT_2, Spell::ENERGY_SOURCE_AIR);
        }
        $units = $this->world->getUnitsAround($x, $y, 1);
        $damage = $this->mage->getDamage(1, Spell::ENERGY_SOURCE_AIR);
        foreach($units as $target) {
            $ux = $target->getX();
            $uy = $target->getY();
            $dx = $ux - $x;
            $dy = $uy - $y;

            $is1Passable = $this->world->isPassable($dx + $ux, $dy + $uy);
            $is2Passable = $this->world->isPassable(($dx * 2) + $ux, ($dy * 2) + $uy);

            if ($is1Passable && $is2Passable) {
                $target->move(($dx * 2) + $ux, ($dy * 2) + $uy, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT);
                $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT_2, Spell::ENERGY_SOURCE_AIR);
            } else if ($is1Passable) {
                $target->move($dx + $ux, $dy + $uy, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT);
                $damage2 = $this->mage->getDamage(2, Spell::ENERGY_SOURCE_AIR);
                $target->damage($damage2, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT_2, Spell::ENERGY_SOURCE_AIR);
            } else {
                $damage3 = $this->mage->getDamage(2, Spell::ENERGY_SOURCE_AIR);
                $target->damage($damage3, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT_2, Spell::ENERGY_SOURCE_AIR);
            }
        }
        return true;
    }
}