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
class IceSpear extends Water
{
    protected $availablePatterns = [4];

    protected function spellEffect($data)
    {
        /**
         * @var Unit $target
         */
        $target = $this->targets[0];
        
        if ($target->getFlag(Unit::FLAG_FROZEN)) {
            $distance = $this->world->getRealDistance($target, $this->mage);

            $damage = floor(6 - $distance);
            if ($damage < 0) {
                $damage = 0;
            }
            $target->damage($damage, $this->getNormalCastStage());

        } else {
            Event::create(
                Event::EVENT_UNIT_BEFORE_TURN, [
                    Event::KEY_TIMES => 3,
                    Event::KEY_OWNER => $target,
                    Event::KEY_ON_COMPLETE => 'Water:RemoveFreeze'
                ],
                'Water:Freeze');
            $target->addFlag(Unit::FLAG_FROZEN);
            GameBuilder::animateEvent(Game::EVENT_NAME_ADD_UNIT_STATUS,
                ['flags' => [Unit::FLAG_FROZEN => true], 'id' => $target->getId()],
                Game::ANIMATION_STAGE_MAGE_ACTION_2);    
        }
        
        return true;
    }
}