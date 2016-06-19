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
class Bomb extends MapObject
{

    protected $damage = 3;
    protected $radius = 1;
    protected $triggerRadius = 1;

    public function trigger($animationStage)
    {
        $mage = GameBuilder::getGame()->getMage();
        $this->damage = $mage->getDamage($this->damage, Spell::ENERGY_SOURCE_FIRE);
        for ($y = -$this->radius; $y <= $this->radius; $y++) {
            for ($x = -$this->radius; $x <= $this->radius; $x++) {
                if ($unit = $this->world->getUnit($this->getX() + $x, $this->getY() + $y)) {
                    $unit->damage($this->damage, Game::ANIMATION_STAGE_TURN_END_EFFECTS_2, Spell::ENERGY_SOURCE_FIRE);
                } else if ($this->getX() + $x == $mage->getX() && $this->getY() + $y == $mage->getY()) {
                    $mage->damage($this->damage, Game::ANIMATION_STAGE_TURN_END_EFFECTS_2, Spell::ENERGY_SOURCE_FIRE);
                }
            }
        }
        
        GameBuilder::animateEvent(Game::EVENT_NAME_OBJECT_ACTIVATE,
            ['action' => 'bombTrigger',
             'targetX' => $this->getX() - $mage->getX(),
             'targetY' => $this->getY() - $mage->getY(),
            ],
            $animationStage);
        
        $this->delete();
    }

    public function activate()
    {
        // ok let`s check is bomb should explode
        for ($y = -$this->triggerRadius; $y <= $this->triggerRadius; $y++) {
            for ($x = -$this->triggerRadius; $x <= $this->triggerRadius; $x++) {
                if ($unit = $this->world->getUnit($this->getX() + $x, $this->getY() + $y)) {
                    $this->trigger(Game::ANIMATION_STAGE_TURN_END_EFFECTS);
                    return;
                }
            }
        }
    }
}