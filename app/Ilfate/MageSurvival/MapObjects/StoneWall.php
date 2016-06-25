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
use Ilfate\MageSurvival\World;

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
class StoneWall extends MapObject
{

    protected $isPassable = false;
    protected $damage = 1;
    protected $radius = 1;

    public function __construct(World $world, $type, $x, $y, $id = null, $data = null)
    {
        parent::__construct($world, $type, $x, $y, $id, $data);
        if (!isset($this->data['turn'])) {
            $this->data['turn'] = GameBuilder::getGame()->getTurn() + 3;
        }
    }

    public function activate()
    {
        if ($this->data['turn'] == GameBuilder::getGame()->getTurn()) {
            $mage = GameBuilder::getGame()->getMage();
            $this->damage = $mage->getDamage($this->damage, Spell::ENERGY_SOURCE_EARTH);
            $units = GameBuilder::getGame()->getWorld()->getUnitsAround($this->getX(), $this->getY(), $this->radius);
            foreach ($units as $unit) {
                $unit->damage($this->damage, Game::ANIMATION_STAGE_TURN_END_EFFECTS_2, Spell::ENERGY_SOURCE_EARTH);
            }

            GameBuilder::animateEvent(Game::EVENT_NAME_OBJECT_ACTIVATE,
                ['action' => 'wallExplode',
                 'targetX' => $this->getX() - $mage->getX(),
                 'targetY' => $this->getY() - $mage->getY(),
                ],
                Game::ANIMATION_STAGE_TURN_END_EFFECTS);
            $this->delete(Game::ANIMATION_STAGE_TURN_END_EFFECTS);
        }
    }
}