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

use Ilfate\MageSurvival\ChanceHelper;
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
class Blizzard extends Water
{
    protected $damageMin = 1;
    protected $damageMax = 2;
    protected $defaultCooldownMin = 4;
    protected $defaultCooldownMax = 6;

    public function setUsages()
    {
        $this->config['usages'] = 3;
    }
    
    protected function spellEffect($data)
    {
        $targets = $this->world->getUnitsAround($this->mage->getX(), $this->mage->getY(), 4);
        foreach ($targets as $target) {
            /**
             * @var Unit $target
             */

            if (!$target->getFlag(Unit::FLAG_FROZEN)) {
                $target->freeze(3, Game::ANIMATION_STAGE_MAGE_ACTION_2);
            }

            $damage = mt_rand($this->damageMin, $this->damageMax);
            $damage = $this->mage->getDamage($damage, Spell::ENERGY_SOURCE_WATER);
            $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_2, Spell::ENERGY_SOURCE_WATER);
        }
        if (ChanceHelper::chance(1)) {
            $this->mage->say('Night gathers, and now my watch begins.', Game::ANIMATION_STAGE_MAGE_BEFORE_ACTION_SPEECH);
            $this->mage->say('It shall not end until my death. I shall take no wife, hold no lands, father no children.', Game::ANIMATION_STAGE_MAGE_AFTER_ACTION_SPEECH);
            $this->mage->say('I shall wear no crowns and win no glory. I shall live and die at my post.', Game::ANIMATION_STAGE_MESSAGE_TIME);
            $this->mage->say('I am the sword in the darkness.', Game::ANIMATION_STAGE_MESSAGE_TIME_2);
            $this->mage->say('I am the watcher on the walls.', Game::ANIMATION_STAGE_MESSAGE_TIME_3);
        }

        return true;
    }
}