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
namespace Ilfate\MageSurvival\Spells\Earth;

use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\Spell;
use Ilfate\MageSurvival\Spells\Earth;
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
 *
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
class Earthquake extends Earth
{
    protected $defaultCooldownMin = 5;
    protected $defaultCooldownMax = 7;

    public function setUsages()
    {
        $this->config['usages'] = 5;
    }

    protected function spellEffect($data)
    {
        $units = $this->world->getUnitsAround($this->mage->getX(), $this->mage->getY(), 5);
        foreach($units as $target) {
            /**
             * @var Unit $target
             */
            $damage = $this->mage->getDamage(mt_rand(1, 2), Spell::ENERGY_SOURCE_EARTH);
            $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT, Spell::ENERGY_SOURCE_EARTH);
        }
        return true;
    }
}