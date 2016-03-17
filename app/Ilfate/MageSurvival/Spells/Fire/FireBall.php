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
class Fireball extends Fire
{
    protected $availablePatterns = [4];

    protected $defaultCooldownMin = 3;
    protected $defaultCooldownMax = 3;

    protected function spellEffect($data)
    {
        foreach($this->targets as $target) {
            /**
             * @var Unit $target
             */
            $target->damage(1, $this->getNormalCastStage());
        }
        return true;
    }
}