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
namespace Ilfate\MageSurvival\Spells\Water;

use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Spells\Water;
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
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
class Freeze extends Water
{
    protected $availablePatterns = [4];

    protected function spellEffect($data)
    {
        $target = $this->targets[0];
        Event::create(
        Event::EVENT_UNIT_BEFORE_MOVE, [
                Event::KEY_TIMES => 3,
                Event::KEY_OWNER => $target,
                Event::KEY_ON_COMPLETE => 'Water:RemoveFreeze' 
            ],
        'Water:Freeze');
        $target->addFlag(Unit::FLAG_FROZEN);
        return true;
    }
}