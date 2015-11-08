<?php
/**
 * TODO: Package description.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @license   Proprietary license.
 * @version   "SVN: $Id$"
 * @link      http://ilfate.net
 */
namespace Ilfate\Cosmos\Ship;

/**
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
trait Rotatable
{
    /**
     * @var int
     */
    protected $d;

    /**
     * @param $x
     * @param $y
     * @param $selfX
     * @param $selfY
     *
     * @return array
     */
    public function convertCoordinatesToShip($x, $y, $selfX, $selfY)
    {
        $shipX = $selfX;
        $shipY = $selfY;
        switch($this->d) {
            case 0:
                $shipX += $x;
                $shipY += $y;
                break;
            case 1:
                $shipX += -$y;
                $shipY += $x;
                break;
            case 2:
                $shipX += -$x;
                $shipY += -$y;
                break;
            case 3:
                $shipX += $y;
                $shipY += -$x;
                break;
        }
        return [$shipX, $shipY];
    }

    /**
     * @param $directions
     *
     * @return array
     */
    public function convertDirectionsToShip($directions)
    {
        $shipDirections = [];
        foreach ($directions as $direction) {
            switch($this->d) {
                case 0:
                    break;
                case 1:
                    $direction++;
                    if ($direction == 4) $direction = 0;
                    break;
                case 2:
                    $direction += 2;
                    if ($direction == 4) $direction = 0;
                    if ($direction == 5) $direction = 1;
                    break;
                case 3:
                    $direction -= 1;
                    if ($direction == -1) $direction = 3;
                    break;
            }
            $shipDirections[] = $direction;
        }

        return $shipDirections;
    }
}