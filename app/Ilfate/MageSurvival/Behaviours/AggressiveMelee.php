<?php namespace Ilfate\MageSurvival\Behaviours;
use Ilfate\MageSurvival\Behaviour;
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
class AggressiveMelee extends Behaviour
{

    const DEFAULT_AGGRESSIVE_RANGE = 5;
    /**
     * @var Unit
     */
    protected $unit;

    public function getAction()
    {
        $unitConfig = $this->unit->getConfig();
        if (!empty($unitConfig['aggressiveRange'])) {
            $aggressiveRange = $unitConfig['aggressiveRange'];
        } else {
            $aggressiveRange = self::DEFAULT_AGGRESSIVE_RANGE;
        }
        $mage = $this->unit->getMage();

        if (World::isNeighbours($mage->getX(), $mage->getY(), $this->unit->getX(), $this->unit->getY())) {
            return self::ACTION_ATTACK_MAGE;
        }
        $distance = World::getDistance($mage, $this->unit);
        if ($distance <= $aggressiveRange) {
            return self::ACTION_MOVE_TO_MAGE;
        }
        return self::ACTION_DO_NOTHING;
    }
}