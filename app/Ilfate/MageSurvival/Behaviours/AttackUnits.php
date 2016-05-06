<?php namespace Ilfate\MageSurvival\Behaviours;
use Ilfate\MageSurvival\Behaviour;
use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\MageSurvival\GameBuilder;
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
class AttackUnits extends Behaviour
{

    const DEFAULT_AGGRESSIVE_RANGE = 2;
    
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
        $world = GameBuilder::getGame()->getWorld();
        $target = $world->getNearestTargetByTeam(Unit::TEAM_TYPE_HOSTILE, $this->unit->getX(), $this->unit->getY(), $aggressiveRange);

        if (!$target) {
            // there is no one to attack
            return self::ACTION_DO_NOTHING;
        }
        
        $this->unit->addTemporaryDataValue('target', $target);

        return self::ACTION_ATTACK_UNIT;
        
    }
}