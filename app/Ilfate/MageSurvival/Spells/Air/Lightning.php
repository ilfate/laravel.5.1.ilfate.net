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

use Ilfate\MageSurvival\ChanceHelper;
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
class Lightning extends Air
{
    protected $defaultCooldownMin = 3;
    protected $defaultCooldownMax = 5;

    public function setUsages()
    {
        $this->config['usages'] = 5;
    }

    protected function spellEffect($data)
    {
        $units = $this->world->getUnitsAround($this->mage->getX(), $this->mage->getY(), 5);
        $units[] = $this->mage;
        $target = ChanceHelper::oneFromArray($units);
        $damage = $this->mage->getDamage(8, Spell::ENERGY_SOURCE_AIR);
        $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_2, Spell::ENERGY_SOURCE_AIR);

        $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
            'spell' => $this->name,
            'targetX' => $target->getX() - $this->mage->getX(),
            'targetY' => $target->getY() - $this->mage->getY()
        ], Game::ANIMATION_STAGE_MAGE_ACTION);
        return true;
    }
}