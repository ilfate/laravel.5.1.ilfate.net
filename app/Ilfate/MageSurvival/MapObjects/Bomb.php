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
namespace Ilfate\MageSurvival\MapObjects;

use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Mage;
use Ilfate\MageSurvival\MapObject;
use Ilfate\MageSurvival\ChanceHelper;

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
class Bomb extends MapObject
{

    protected $damage = 3;
    protected $radius = 1;

    public function trigger($animationStage)
    {
        for ($y = -1; $y <=1; $y++) {
            for ($x = -1; $x <=1; $x++) {
                if ($unit = $this->world->getUnit($this->getX() + $x, $this->getY() + $y)) {
                    $unit->damage($this->damage, $animationStage);
                }
            }
        }

        $this->delete();
    }
}