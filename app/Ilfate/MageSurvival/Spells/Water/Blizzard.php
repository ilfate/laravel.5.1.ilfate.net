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
class Blizzard extends Water
{
    protected $defaultCooldownMin = 4;
    protected $defaultCooldownMax = 6;
    
    protected function spellEffect($data)
    {
        $targets = $this->world->getUnitsAround($this->mage->getX(), $this->mage->getY(), 4);
        foreach ($targets as $target) {
            /**
             * @var Unit $target
             */

            if (!$target->getFlag(Unit::FLAG_FROZEN)) {
                Event::create(
                    Event::EVENT_UNIT_BEFORE_TURN, [
                    Event::KEY_TIMES       => 3,
                    Event::KEY_OWNER       => $target,
                    Event::KEY_ON_COMPLETE => 'Water:RemoveFreeze'
                ],
                    'Water:Freeze');
                $target->addFlag(Unit::FLAG_FROZEN);
                GameBuilder::animateEvent(Game::EVENT_NAME_ADD_UNIT_STATUS,
                    ['flags' => [Unit::FLAG_FROZEN => true], 'id' => $target->getId()],
                    Game::ANIMATION_STAGE_MAGE_ACTION_2);
            }

            $damage = mt_rand(1, 2);
            $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_2);
        }

        return true;
    }
}