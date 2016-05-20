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
class AggressiveRange extends Behaviour
{

    const DEFAULT_AGGRESSIVE_RANGE = 5;

    public function getAction()
    {
        $mage = $this->unit->getMage();

        $attack = $this->unit->getPossibleAttack($mage);

        if ($attack) {
            return self::ACTION_ATTACK_MAGE;
        }
        $this->unit->addTemporaryDataValue('target', $mage);
        return self::ACTION_MOVE_TO_TARGET;
    }
}