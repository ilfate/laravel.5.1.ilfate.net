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
class Aggressive extends Behaviour
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
        $distance = World::getDistance($this->unit->getMage(), $this->unit);
        if ($distance <= $aggressiveRange && $distance > 2) {
            return self::ACTION_MOVE_TO_MAGE;
        }
        return self::ACTION_DO_NOTHING;
    }
}