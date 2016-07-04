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
class IceSlide extends Water
{
    protected $defaultCooldownMin = 0;
    protected $defaultCooldownMax = 1;
    
    protected $availablePatterns = [4];

    protected function spellEffect($data)
    {
        $targets = $this->world->getUnitsAround($this->mage->getX(), $this->mage->getY(), 1);
        foreach ($targets as $target) {
            $target->freeze(2, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT);
        }
        Event::create(Event::EVENT_MAGE_AFTER_MOVE, ['times' => 1], 'Water:iceSlide');
        
        return true;
    }
}