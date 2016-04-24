<?php namespace Ilfate\MageSurvival\Spells\Fire;

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
class ExplodingBees extends Fire
{

    protected $defaultCooldownMin = 4;
    protected $defaultCooldownMax = 7;

    protected function spellEffect($data)
    {
        foreach($this->targets as $target) {
            /**
             * @var Unit $target
             */
            $target->damage(mt_rand(4, 6), $this->getNormalCastStage());
        }
        return true;
    }
}