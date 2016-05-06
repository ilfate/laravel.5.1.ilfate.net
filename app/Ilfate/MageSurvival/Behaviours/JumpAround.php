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
class JumpAround extends Behaviour
{

    public function getAction()
    {
        //$mage = $this->unit->getMage();
        $world = $this->unit->getWorld();

        $possibleLandings = [];
        for ($iy = -1; $iy <= 1; $iy ++) {
            for ($ix = -1; $ix <= 1; $ix ++) {
                $ax = $this->unit->getX() + $ix;
                $ay = $this->unit->getY() + $iy;
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
}