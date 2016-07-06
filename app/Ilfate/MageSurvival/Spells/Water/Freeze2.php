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
class Freeze2 extends Water
{
    protected $defaultCooldownMin = 5;
    protected $defaultCooldownMax = 6;

    public function setUsages()
    {
        $this->config['usages'] = 5;
    }

    protected function spellEffect($data)
    {

        $target = $this->targets[0];
        $damage = $this->mage->getDamage(3, Spell::ENERGY_SOURCE_WATER);
        $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT, Spell::ENERGY_SOURCE_WATER);
        $target->freeze(4, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT);
        return true;
    }
}