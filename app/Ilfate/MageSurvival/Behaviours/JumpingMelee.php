<?php namespace Ilfate\MageSurvival\Behaviours;
use Ilfate\MageSurvival\AliveCommon;
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
class JumpingMelee extends AggressiveMelee
{



    public function getAction()
    {
        $action = parent::getAction();
        if ($action == self::ACTION_MOVE_TO_TARGET && ChanceHelper::chance(90)) {
            /**
             * @var AliveCommon $target
             */
            $target = $this->unit->getTemporaryDataValue('target');
            $tx     = $target->getX();
            $ty     = $target->getY();
            $ux     = $this->unit->getX();
            $uy     = $this->unit->getY();
            $dx     = $tx - $ux;
            $dy     = $ty - $uy;
            if (abs($dx) > 1) {
                $dx = mt_rand(1, abs($dx) - 1) * ($dx < 0 ? -1 : 1);
            }
            if (abs($dy) > 1) {
                $dy = mt_rand(1, abs($dy) - 1) * ($dy < 0 ? -1 : 1);
            }
            if (GameBuilder::getGame()->getWorld()->isPassable($ux + $dx, $uy + $dy)) {
                $this->unit->addTemporaryDataValue('landing', [$ux + $dx, $uy + $dy]);
                $action = self::ACTION_JUMP_TO;
            }
        }
        return $action;
    }
}