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
class IceCone extends Water
{
    protected $availablePatterns = [16, 17, 18];

    protected function spellEffect($data)
    {
        foreach ($this->targets as $target) {
            /**
             * @var Unit $target
             */

            if (!$target->getFlag(Unit::FLAG_FROZEN)) {
                $target->freeze(2, Game::ANIMATION_STAGE_MAGE_ACTION_2);
            }

            $damage = mt_rand(1, 2);
            $damage = $this->mage->getDamage($damage, Spell::ENERGY_SOURCE_WATER);
            $target->damage($damage, $this->getNormalCastStage(), Spell::ENERGY_SOURCE_WATER);
        }
        
        
        return true;
    }
}