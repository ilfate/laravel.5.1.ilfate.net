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
namespace Ilfate\MageSurvival\Spells\Fire;

use Ilfate\MageSurvival\Spells\DamageSpell;
use Ilfate\MageSurvival\Spells\Fire;
use Ilfate\MageSurvival\Spells\FireDamageSpell;
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
class FaceCanon extends Fire
{
    protected $availablePatterns = [5,6,7];

    protected $defaultCooldownMin = 2;
    protected $defaultCooldownMax = 6;

    protected function spellEffect($data)
    {
        foreach($this->targets as $target) {
            $damage = mt_rand(1, 6);
            /**
             * @var Unit $target
             */
            $target->damage($damage, $this->getNormalCastStage());
        }
        //$this->setNexStage();
        $x = $this->mage->getX();
        $y = $this->mage->getY();
        $d = $this->mage->getD();
        switch ($d) {
            case 0: $move = function($x, $y) {$y += 1; return [$x, $y];}; break;
            case 1: $move = function($x, $y) {$x -= 1; return [$x, $y];}; break;
            case 2: $move = function($x, $y) {$y -= 1; return [$x, $y];}; break;
            case 3: $move = function($x, $y) {$x += 1; return [$x, $y];}; break;
            default: throw new \Exception('Direction was wrong');
        }
        for ($i = 0; $i < 3; $i++) {
            list($nx, $ny) = $move($x, $y);
            if (!$this->world->isPassable($nx, $ny)) {
                break;
            }
            $x = $nx; $y = $ny;
        }
        $this->mage->forceMove($x, $y, $this->getNormalCastStage());
        return true;
    }
}