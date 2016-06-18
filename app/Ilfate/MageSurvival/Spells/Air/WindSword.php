<?php
/**
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 *
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
namespace Ilfate\MageSurvival\Spells\Air;

use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Spell;
use Ilfate\MageSurvival\Spells\Air;
use Ilfate\MageSurvival\Unit;

/**
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
class WindSword extends Air
{
    protected $defaultCooldownMin = 3;
    protected $defaultCooldownMax = 6;
    protected $availablePatterns = [26,27];

    protected function spellEffect($data)
    {
        $ignoreUnits = [];
        foreach ($this->pattern as $cell) {
            $x = $cell[0] + $this->mage->getX();
            $y = $cell[1] + $this->mage->getY();
            $damaged = $this->pushAllAround($x, $y, 1, $ignoreUnits);
            $ignoreUnits = array_merge($ignoreUnits, $damaged);
            if ($unit = $this->world->getUnit($x, $y)) {
                if (!in_array($unit->getId(), $ignoreUnits)) {
                    $damage = $this->mage->getDamage(1, Spell::ENERGY_SOURCE_AIR);
                    $unit->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT_2, Spell::ENERGY_SOURCE_AIR);
                    $ignoreUnits[] = $unit->getId();
                }
            }
        }
        return true;
    }
}