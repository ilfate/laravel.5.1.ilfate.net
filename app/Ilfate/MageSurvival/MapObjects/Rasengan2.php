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
namespace Ilfate\MageSurvival\MapObjects;

use Ilfate\Geometry2DCells;
use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Mage;
use Ilfate\MageSurvival\MapObject;
use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\MageSurvival\Spell;
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
class Rasengan2 extends MapObject
{

    protected $isPassable = false;

    public function activate()
    {
        $x = $this->getX();
        $y = $this->getY();
        switch ($this->data['d']) {
            case 0: $y --; break;
            case 1: $x ++; break;
            case 2: $y ++; break;
            case 3: $x --; break;
        }
        $world = GameBuilder::getGame()->getWorld();
        $objectThere = $world->getObject($x, $y);
        if ($objectThere) {
            $this->delete(Game::ANIMATION_STAGE_TURN_END_EFFECTS);
            return;
        }
        $this->move($x, $y, Game::ANIMATION_STAGE_TURN_END_EFFECTS);

        $damageOnPush = 1;
        $units = $world->getUnitsAround($x, $y, 1);
        $mage = GameBuilder::getGame()->getMage();
        $damage = $mage->getDamage($damageOnPush, Spell::ENERGY_SOURCE_AIR);

        foreach($units as $target) {
            $ux = $target->getX();
            $uy = $target->getY();
            $dx = $ux - $x;
            $dy = $uy - $y;

            $is1Passable = $world->isPassable($dx + $ux, $dy + $uy);
            $is2Passable = $world->isPassable(($dx * 2) + $ux, ($dy * 2) + $uy);

            if ($is1Passable && $is2Passable) {
                $target->move(($dx * 2) + $ux, ($dy * 2) + $uy, Game::ANIMATION_STAGE_TURN_END_EFFECTS);
                $target->damage($damage, Game::ANIMATION_STAGE_TURN_END_EFFECTS_2, Spell::ENERGY_SOURCE_AIR);
            } else if ($is1Passable) {
                $target->move($dx + $ux, $dy + $uy, Game::ANIMATION_STAGE_TURN_END_EFFECTS);
                $damage2 = $mage->getDamage($damageOnPush + 1, Spell::ENERGY_SOURCE_AIR);
                $target->damage($damage2, Game::ANIMATION_STAGE_TURN_END_EFFECTS_2, Spell::ENERGY_SOURCE_AIR);
            } else {
                $damage3 = $mage->getDamage($damageOnPush + 2, Spell::ENERGY_SOURCE_AIR);
                $target->damage($damage3, Game::ANIMATION_STAGE_TURN_END_EFFECTS_2, Spell::ENERGY_SOURCE_AIR);
            }
        }
        
        return ;
    }
}