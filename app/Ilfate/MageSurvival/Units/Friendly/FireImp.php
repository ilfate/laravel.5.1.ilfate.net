<?php namespace Ilfate\MageSurvival\Units\Friendly;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Spell;
use Ilfate\MageSurvival\Units\Friendly;

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
class FireImp extends Friendly
{
    protected function chargesForAttackAreOver($attackConfig) {
        $radius = 2;
        $damage = 2;
        $damage = GameBuilder::getGame()->getMage()->getDamage($damage, Spell::ENERGY_SOURCE_FIRE);
        $world = GameBuilder::getGame()->getWorld();
        for ($y = -$radius; $y <= $radius; $y++) {
            for ($x = -$radius; $x <= $radius; $x++) {
                if ($unit = $world->getUnit($this->getX() + $x, $this->getY() + $y)) {
                    $unit->damage($damage, Game::ANIMATION_STAGE_UNIT_ACTION_3, Spell::ENERGY_SOURCE_FIRE);
                }
            }
        }
        $this->dead(Game::ANIMATION_STAGE_UNIT_ACTION_2);
    }
}