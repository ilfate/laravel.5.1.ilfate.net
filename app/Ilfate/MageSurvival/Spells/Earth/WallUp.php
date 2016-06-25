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

use Ilfate\Geometry2DCells;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
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
class WallUp extends Earth
{
    protected $defaultCooldownMin = 8;
    protected $defaultCooldownMax = 12;

    public function setUsages()
    {
        $this->config['usages'] = 3;
    }

    protected function spellEffect($data)
    {
        $units = $this->world->getUnitsAround($this->mage->getX(), $this->mage->getY(), 1);
        foreach($units as $target) {
            /**
             * @var Unit $target
             */
            $target->stone(3, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT);
        }
        $cells = Geometry2DCells::getNeighbours($this->mage->getX(), $this->mage->getY());
        foreach ($cells as $cell) {
            if ($this->world->isPassable($cell[0], $cell[1]) && !$this->world->getObject($cell[0], $cell[1])) {
                $object = $this->world->addObject(6, $cell[0], $cell[1]);
                if ($object) {
                    GameBuilder::animateEvent(Game::EVENT_NAME_ADD_OBJECT,
                        ['object' => $object->exportForView()],
                        Game::ANIMATION_STAGE_MAGE_ACTION_3);
                }
            }
        }


        return true;
    }
}