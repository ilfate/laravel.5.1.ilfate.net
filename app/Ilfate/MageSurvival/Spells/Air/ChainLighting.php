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
class ChainLighting extends Air
{
    protected $defaultCooldownMin = 3;
    protected $defaultCooldownMax = 4;
    
    protected $jumpsTimes = 2;

    public function setUsages()
    {
        $this->config['usages'] = 6;
    }

    protected function spellEffect($data)
    {
        $damageValue = 1;
        $targets = [];
        $alreadyDamaged = [];
        $target = $this->targets[0];
        $damage = $this->mage->getDamage($damageValue, Spell::ENERGY_SOURCE_AIR);
        $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_3, Spell::ENERGY_SOURCE_AIR);
        $targets[] = [$target->getX() - $this->mage->getX(), $target->getY() - $this->mage->getY()];
        $alreadyDamaged[] = $target->getId();
        $damageValue++;

        for ($i = 0; $i < $this->jumpsTimes; $i++) {
            $nextUnits  = $this->world->getUnitsAround($target->getX(), $target->getY(), 2);
            $nextTarget = false;
            while (!$nextTarget && $nextUnits) {
                $potentialTarget = ChanceHelper::extractOne($nextUnits);
                if (!in_array($potentialTarget->getId(), $alreadyDamaged)) {
                    $nextTarget = $potentialTarget;
                    break;
                }
            }
            if ($nextTarget) {
                $target = $nextTarget;
                $damage = $this->mage->getDamage($damageValue, Spell::ENERGY_SOURCE_AIR);
                $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_3, Spell::ENERGY_SOURCE_AIR);
                $targets[]        = [$target->getX() - $this->mage->getX(), $target->getY() - $this->mage->getY()];
                $alreadyDamaged[] = $target->getId();
                $damageValue++;
            } else {
                break;
            }
        }
        $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
            'spell' => $this->name,
            'targets' => $targets,
        ], Game::ANIMATION_STAGE_MAGE_ACTION_2);

        return true;
    }
}