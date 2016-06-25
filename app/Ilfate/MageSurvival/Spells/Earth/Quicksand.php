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

use Ilfate\MageSurvival\AliveCommon;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\Spells\Earth;

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
class Quicksand extends Earth
{
    protected $defaultCooldownMin = 4;
    protected $defaultCooldownMax = 6;

    protected function spellEffect($data)
    {
        $x = $this->mage->getX();
        $y = $this->mage->getY();
        $targets = $this->world->getUnitsAround($x, $y, 5);
        $animationTargets = [];
        foreach ($targets as $target) {
            //$target->addFlag(AliveCommon::FLAG_QUICKSAND, $this->game->getTurn() + 2);
            if ($target->sand(2, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT)) {
                $animationTargets[] = [$target->getX() - $this->mage->getX(), $target->getY() - $this->mage->getY()];
            }
        }
        $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
            'spell' => $this->name,
            'targets' => $animationTargets,
        ], Game::ANIMATION_STAGE_MAGE_ACTION_2);
        
        return true;
    }
}