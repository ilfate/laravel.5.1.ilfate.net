<?php
/**
 * TODO: Package description.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @copyright 2016 Watchmaster GmbH
 * @license   Proprietary license.
 * @link      http://www.watchmaster.de
 */
namespace Ilfate;

/**
 * TODO: Short description.
 * TODO: Long description here.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @copyright 2016 Watchmaster GmbH
 * @license   Proprietary license.
 * @link      http://www.watchmaster.de
 */
class Geometry2DCells
{
    public static function getNeighbours($x = false, $y = false)
    {
        if ($x === false && $y === false) {
            return [
                [0, -1],
                [1, 0],
                [0, 1],
                [-1, 0],
                [-1, -1],
                [1, -1],
                [1, 1],
                [-1, 1],
            ];
        }
        return [
            [$x, $y - 1],
            [$x + 1, $y],
            [$x, $y + 1],
            [$x - 1, $y],
            [$x - 1, $y - 1],
            [$x + 1, $y - 1],
            [$x + 1, $y + 1],
            [$x - 1, $y + 1],
        ];
    }

    public static function getAngleBetween2Dots($x1, $y1, $x2, $y2)
    {
        $deltaY = $y2 - $y1;
        $deltaX = $x2 - $x1;
        $rad = self::angle_trunc(atan2($deltaY, $deltaX));
        $deg = $rad * (180 / pi());
        $distance = sqrt(pow($deltaX, 2) + pow($deltaY, 2));
        return [$distance, $deg];
    }

    public static function isNeighbours($x1, $y1, $x2, $y2)
    {
        if (abs($x1 - $x2) <= 1 && abs($y1 - $y2) <= 1) {
            return true;
        }
        return false;
    }

    protected static function angle_trunc($a) {
        while ($a < 0.0) {
            $a += pi() * 2;
        }
        return $a;
    }
}