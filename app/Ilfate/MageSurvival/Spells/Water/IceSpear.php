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
            if ($damage < 1) {
                $damage = 1;
            }
            $damage = $this->mage->getDamage($damage, Spell::ENERGY_SOURCE_WATER);
            $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT, Spell::ENERGY_SOURCE_WATER);

        } else {
            $target->freeze(3, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT);
        }
        
        return true;
    }
}