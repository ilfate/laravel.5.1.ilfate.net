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
class Rasengan extends MapObject
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
        
        $units = $this->world->getUnitsAround($x, $y, 1);

        foreach ($units as $unit) {
            if (!$unit->getFlag(Unit::FLAG_FROZEN)) {
                $unit->freeze(1, Game::ANIMATION_STAGE_TURN_END_EFFECTS_2);
            }

            $damage = GameBuilder::getGame()->getMage()->getDamage(1, Spell::ENERGY_SOURCE_WATER);
            $unit->damage($damage, Game::ANIMATION_STAGE_TURN_END_EFFECTS_2, Spell::ENERGY_SOURCE_WATER);
        }
        
        return ;
    }
}