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
namespace Ilfate\MageSurvival\Spells\Water;

use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Spells\Water;
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
class IceWall extends Water
{
    protected $availablePatterns = [14, 15];

    protected function spellEffect($data)
    {
        foreach ($this->affectedCells as $affectedCell) {
            if ($unit = $this->world->getUnit($affectedCell[0], $affectedCell[1])) {
                Event::create(
                    Event::EVENT_UNIT_BEFORE_TURN, [
                    Event::KEY_TIMES => 5,
                    Event::KEY_OWNER => $unit,
                    Event::KEY_ON_COMPLETE => 'Water:RemoveFreeze'
                ],
                    'Water:Freeze');
                $unit->addFlag(Unit::FLAG_FROZEN);
                GameBuilder::animateEvent(Game::EVENT_NAME_ADD_UNIT_STATUS,
                    ['flags' => [Unit::FLAG_FROZEN => true], 'id' => $unit->getId()],
                    Game::ANIMATION_STAGE_MAGE_ACTION_3);
            } else if ($this->world->isPassable($affectedCell[0], $affectedCell[1])) {
                $object = $this->world->addObject(5, $affectedCell[0], $affectedCell[1]);
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