<?php namespace Ilfate\MageSurvival\Behaviours;
use Ilfate\MageSurvival\Behaviour;
use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\MageSurvival\Unit;
use Ilfate\MageSurvival\World;

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
class Follow extends Behaviour
{

    const FOLLOW_DISTANCE = 3;
    const JUMP_DISTANCE = 6;

    public function getAction()
    {
        $unitConfig = $this->unit->getConfig();
        if (!empty($unitConfig['followDistance'])) {
            $followDistance = $unitConfig['followDistance'];
        } else {
            $followDistance = self::FOLLOW_DISTANCE;
        }
        $mage = $this->unit->getMage();
        $world = $this->unit->getWorld();

        $realDistance = World::getRealDistance($mage, $this->unit);

        if ($realDistance > self::JUMP_DISTANCE) {
            $possibleLandings = [];
            for ($iy = -1; $iy <= 1; $iy ++) {
                for ($ix = -1; $ix <= 1; $ix ++) {
                    $ax = $mage->getX() + $ix;
                    $ay = $mage->getY() + $iy;
                    if ($world->isPassable($ax, $ay)) {
                        $possibleLandings[] = [$ax, $ay];
                    }
                }
            }
            if (!$possibleLandings) {
                return self::ACTION_DO_NOTHING;
            }
            $landing = ChanceHelper::oneFromArray($possibleLandings);
            $this->unit->addTemporaryDataValue('landing', $landing);
            return self::ACTION_JUMP_TO;
        }
        if ($realDistance > $followDistance) {
            $this->unit->addTemporaryDataValue('target', $mage);
            return self::ACTION_MOVE_TO_TARGET;
        }
        return self::ACTION_DO_NOTHING;
    }
}