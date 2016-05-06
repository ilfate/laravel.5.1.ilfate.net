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
namespace Ilfate\MageSurvival;

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
abstract class Behaviour
{
    const ACTION_DO_NOTHING   = 'do-nothing';
    const ACTION_MOVE_TO_TARGET = 'move-to-target';
    const ACTION_JUMP_TO =  'jump-to';
    const ACTION_ATTACK_MAGE  = 'attack-mage';
    const ACTION_ATTACK_UNIT  = 'attack-unit';

    /**
     * @var Unit
     */
    protected $unit;

    public function __construct(Unit $unit)
    {
        $this->unit = $unit;
    }

    public function getAction()
    {
        return self::ACTION_DO_NOTHING;
    }
}