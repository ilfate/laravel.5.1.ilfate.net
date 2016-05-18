<?php namespace Ilfate\MageSurvival\Spells\Fire;

use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Spells\Fire;
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
class LightMyFire extends Fire
{

    protected $defaultCooldownMin = 2;
    protected $defaultCooldownMax = 4;

    protected function spellEffect($data)
    {
        foreach($this->targets as $target) {
            /**
             * @var Unit $target
             */
            Event::create(
                Event::EVENT_UNIT_AFTER_TURN, [
                Event::KEY_TIMES => 4,
                Event::KEY_OWNER => $target,
                Event::KEY_ON_COMPLETE => 'Fire:RemoveBurn'
            ],
                'Fire:Burn');
            $target->addFlag(Unit::FLAG_BURN);
            
        }
        return true;
    }
}