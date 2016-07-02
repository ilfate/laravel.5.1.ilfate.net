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
use Ilfate\MageSurvival\Spell;
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
    protected $defaultCooldownMin = 3;
    protected $defaultCooldownMax = 4;
    
    protected $availablePatterns = [14, 15];

    protected function spellEffect($data)
    {
        $damage = $this->mage->getDamage(2, Spell::ENERGY_SOURCE_WATER);
        foreach ($this->affectedCells as $affectedCell) {
            if ($unit = $this->world->getUnit($affectedCell[0], $affectedCell[1])) {
                $unit->freeze(5, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT);
                $unit->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT, Spell::ENERGY_SOURCE_WATER);
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