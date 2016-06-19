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
namespace Ilfate\MageSurvival\Spells\Air;

use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Spell;
use Ilfate\MageSurvival\Spells\Air;
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
class Push2 extends Air
{
    protected $defaultCooldownMin = 4;
    protected $defaultCooldownMax = 6;

    public function setUsages()
    {
        $this->config['usages'] = 5;
    }

    protected function spellEffect($data)
    {
        $mx = $this->mage->getX();
        $my = $this->mage->getY();
        $targets = $this->world->getUnitsAround($mx, $my, 2, [Unit::TEAM_TYPE_HOSTILE]);
        foreach($targets as $target) {
            /**
             * @var Unit $target
             */
            $push = function($x, $y) use ($target, $mx, $my) {
                return [$x + $target->getX() - $mx, $y + $target->getY() - $my];
            };
            $cell1 = $push($target->getX(), $target->getY());
            $cell2 = $push($cell1[0], $cell1[1]);
            $is1Passable = $this->world->isPassable($cell1[0], $cell1[1]);
            $is2Passable = $this->world->isPassable($cell2[0], $cell2[1]);
            $damage = $this->mage->getDamage(2, Spell::ENERGY_SOURCE_AIR);
            if ($is1Passable && $is2Passable) {
                $target->move($cell2[0], $cell2[1], Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT);
            } else if ($is1Passable) {
                $target->move($cell1[0], $cell1[1], Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT);
            }
            $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT_2, Spell::ENERGY_SOURCE_AIR);
        }
        return true;
    }
}