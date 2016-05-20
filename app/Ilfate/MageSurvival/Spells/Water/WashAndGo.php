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

use Ilfate\Geometry2DCells;
use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\MessageException;
use Ilfate\MageSurvival\Spells\Water;

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
class WashAndGo extends Water
{
    protected $defaultCooldownMin = 10;
    protected $defaultCooldownMax = 15;

    protected $availablePatterns = [4];

    protected function spellEffect($data)
    {
        //Event::create(Event::EVENT_MAGE_BEFORE_GET_DAMAGE, ['times' => 3], 'Water:iceCrown');
        $cells = Geometry2DCells::getNeighbours();
        $doWeHaveWater = false;
        $mageX = $this->mage->getX();
        $mageY = $this->mage->getY();
        foreach ($cells as $cell) {
            $cellValue = $this->world->getCell($mageX + $cell[0], $mageY + $cell[1]);
            if (GameBuilder::getGame()->getWorldGenerator()->isWater($cellValue)) {
                $doWeHaveWater = $cell;
                break;
            }
        }
        if (!$doWeHaveWater) {
            throw new MessageException('There is no water around you');
        }
        $healValue = $this->mage->getMaxHealth() / 100 * 30;
        $this->mage->heal($healValue, Game::ANIMATION_STAGE_MAGE_ACTION_2);
        $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
            'spell' => $this->name,
            'targetX' => $doWeHaveWater[0],
            'targetY' => $doWeHaveWater[1],
        ], Game::ANIMATION_STAGE_MAGE_ACTION);
        
        return true;
    }
}