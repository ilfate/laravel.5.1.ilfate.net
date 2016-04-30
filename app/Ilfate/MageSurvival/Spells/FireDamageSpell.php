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
trait FireDamageSpell
{
    protected function spellEffect($data)
    {

        foreach($this->targets as $target) {
            /**
             * @var Unit $target
             */
            $target->damage($this->damage, $this->getNormalCastStage());
        }
        foreach ($this->affectedCells as $affectedCell) {
            $newCell = $this->game->getWorldGenerator()->getCellDestroyableBySource(
                $affectedCell[0], $affectedCell[1], Spell::ENERGY_SOURCE_FIRE);
            if ($newCell) {
                $mageX = $mage = $this->mage->getX();
                $mageY = $mage = $this->mage->getY();
                $this->world->setCell($affectedCell[0], $affectedCell[1], $newCell);
                $this->game->addAnimationEvent(Game::EVENT_CELL_CHANGE, [
                    'cell' => $newCell, 'targetX' => $affectedCell[0] - $mageX, 'targetY' => $affectedCell[1] - $mageY
                ], $this->getNormalCastStage());
            }
        }
        return true;
    }
}