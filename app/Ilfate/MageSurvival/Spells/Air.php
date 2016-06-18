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
namespace Ilfate\MageSurvival\Spells;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\Spell;

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
abstract class Air extends Spell
{
    protected function pushAllAround($x, $y, $damageOnPush, $excludeTargetIds = [])
    {
        $units = $this->world->getUnitsAround($x, $y, 1);
        $damage = $this->mage->getDamage($damageOnPush, Spell::ENERGY_SOURCE_AIR);
        $targetsThatGotDamage = [];
        foreach($units as $target) {
            if (in_array($target->getId(), $excludeTargetIds)) {
                continue;
            }
            $targetsThatGotDamage[] = $target->getId();
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
                $damage2 = $this->mage->getDamage($damageOnPush + 1, Spell::ENERGY_SOURCE_AIR);
                $target->damage($damage2, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT_2, Spell::ENERGY_SOURCE_AIR);
            } else {
                $damage3 = $this->mage->getDamage($damageOnPush + 2, Spell::ENERGY_SOURCE_AIR);
                $target->damage($damage3, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT_2, Spell::ENERGY_SOURCE_AIR);
            }
        }
        return $targetsThatGotDamage;
    }
}