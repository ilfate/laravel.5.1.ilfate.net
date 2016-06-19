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
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Mage;
use Ilfate\MageSurvival\MapObject;
use Ilfate\MageSurvival\ChanceHelper;
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
class TeslaTrap extends MapObject
{
    protected $damage = 1;
    protected $radius = 1;

    public function activate()
    {
        $units = $this->world->getUnitsAround($this->getX(), $this->getY(), $this->radius);
        $mage = GameBuilder::getGame()->getMage();
        $this->damage = $mage->getDamage($this->damage, Spell::ENERGY_SOURCE_AIR);
        $targets = [];
        if ($units) {
            foreach ($units as $unit) {
                $unit->damage($this->damage, Game::ANIMATION_STAGE_TURN_END_EFFECTS_2, Spell::ENERGY_SOURCE_AIR);
                $targets[] = [$unit->getX() - $mage->getX(), $unit->getY() - $mage->getY()];
            }
        }
        if (Geometry2DCells::isNeighbours($mage->getX(), $mage->getY(), $this->getX(), $this->getY())) {
            $mage->damage($this->damage, Game::ANIMATION_STAGE_TURN_END_EFFECTS_2, Spell::ENERGY_SOURCE_AIR);
            $targets[] = [0, 0];
        }
        if ($targets) {
            GameBuilder::animateEvent(Game::EVENT_NAME_OBJECT_ACTIVATE,
                ['action' => 'lightingZap',
                 'targets' => $targets,
                 'centerX' => $this->getX() - $mage->getX(),
                 'centerY' => $this->getY() - $mage->getY(),
                ],
                Game::ANIMATION_STAGE_TURN_END_EFFECTS);
        }
        
    }
}