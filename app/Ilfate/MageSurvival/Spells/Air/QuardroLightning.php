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
class QuardroLightning extends Air
{
    protected $defaultCooldownMin = 3;
    protected $defaultCooldownMax = 5;

    protected function spellEffect($data)
    {
        $units = $this->world->getUnitsAround($this->mage->getX(), $this->mage->getY(), 5);
        $targets = [];
        $animationTargets = [];
        for ($i = 0; $i < 4; $i ++) {
            if (!$units) break;
            $targets[] = ChanceHelper::extractOne($units);
        }
        if ($targets) {
            $damage = $this->mage->getDamage(1, Spell::ENERGY_SOURCE_AIR);
            foreach ($targets as $target) {
                $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_2, Spell::ENERGY_SOURCE_AIR);
                $animationTargets[] = [$target->getX() - $this->mage->getX(), $target->getY() - $this->mage->getY()];
            }
        }

        $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
            'spell' => $this->name,
            'targets' => $animationTargets,
        ], Game::ANIMATION_STAGE_MAGE_ACTION);
        return true;
    }
}