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
class TunnelTravel extends Earth
{
    protected $defaultCooldownMin = 4;
    protected $defaultCooldownMax = 6;

    protected function spellEffect($data)
    {
        /**
         * @var Unit $target
         */
        $target = $this->targets[0];
        $destinationX = $target->getX();
        $destinationY = $target->getY();
        $departureX = $this->mage->getX();
        $departureY = $this->mage->getY();

        $this->mage->forceMove($destinationX, $destinationY, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT);
        $target->move($departureX, $departureY, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT_2, true);

        return true;
    }
}