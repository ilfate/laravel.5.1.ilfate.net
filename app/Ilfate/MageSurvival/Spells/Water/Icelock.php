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
class Icelock extends Water
{
    protected $defaultCooldownMin = 3;
    protected $defaultCooldownMax = 5;
    protected $damageMin = 3;

    protected function spellEffect($data)
    {
        //$targets = $this->world->getUnitsAround($this->mage->getX(), $this->mage->getY(), 4);
        $units = GameBuilder::getGame()->getWorldGenerator()->getActiveUnits($this->mage);
        $animationCoords = [];
        $damage = $this->mage->getDamage($this->damageMin, Spell::ENERGY_SOURCE_WATER);
        foreach ($units as $target) {
            /**
             * @var Unit $target
             */
            if (!$target->isAlive() || !$target->getFlag(Unit::FLAG_FROZEN)) {
                continue;
            }

            $animationCoords[] = [$target->getX() - $this->mage->getX(), $target->getY() - $this->mage->getY()];
            $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_2, Spell::ENERGY_SOURCE_WATER);
        }
        $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
            'spell' => $this->name,
            'data' => $animationCoords, 'd' => $this->mage->getD()
        ], Game::ANIMATION_STAGE_MAGE_ACTION);

        return true;
    }
}