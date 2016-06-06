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
            $target->burn(4, $this->getNormalCastStage());
            
        }
        return true;
    }
}