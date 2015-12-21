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
namespace Ilfate\Hex;

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
class Gun extends Cell
{

    const LASER_TYPE_0 = 0;
    const LASER_TYPE_1 = 1;
    const LASER_TYPE_2 = 2;

    protected static $laserPerDirection = [
        0 => self::LASER_TYPE_0,
        1 => self::LASER_TYPE_1,
        2 => self::LASER_TYPE_2,
        3 => self::LASER_TYPE_0,
        4 => self::LASER_TYPE_1,
        5 => self::LASER_TYPE_2,
    ];

    /**
     *
     */
    public function setUpGuns()
    {
        $result = [];
        if ($this->getType() == self::TYPE_GUN) {
            $neighbors = $this->field->getNeighborsForCell($this->x, $this->y);
            for ($direction = 0; $direction < 6; $direction++) {
                if (empty($neighbors[$direction])) {
                    //
                    $oppositeDirection = $this->field->getOppositeDirection($direction);
                    $oppositeCell = $neighbors[$oppositeDirection];
                    if ($oppositeCell && $oppositeCell->getType() != 'gun') {
                        $result[] = $oppositeDirection;
                        $length = $this->getCellsLengthToObstacle(
                            $oppositeDirection,
                            $this->getLaserTypeForDirection($oppositeDirection)
                        );
                        $this->lasers[$oppositeDirection] = $length;
                    }
                }
            }
            $this->setGuns($result);
        }
    }

    public function getLaserTypeForDirection($direction)
    {
        return self::$laserPerDirection[$direction];
    }

    /**
     * @return array
     */
    public function checkGunsForChanges()
    {
        $changedLasers = [];
        $neighbors = $this->field->getNeighborsForCell($this->x, $this->y);
        for ($direction = 0; $direction < 6; $direction++) {
            if (empty($neighbors[$direction])) {
                //
                $oppositeDirection = $this->field->getOppositeDirection($direction);
                $oppositeCell = $neighbors[$oppositeDirection];
                if ($oppositeCell && $oppositeCell->getType() != 'gun') {
                    $result[] = $oppositeDirection;
                    $length = $this->getCellsLengthToObstacle(
                        $oppositeDirection,
                        $this->getLaserTypeForDirection($oppositeDirection)
                    );
                    if ($this->lasers[$oppositeDirection] !=  $length) {
                        $changedLasers[$oppositeDirection] = $this->cellsToEm($length);
                    }
                }
            }
        }
        return $changedLasers;
    }



    /**
     * @param $direction
     *
     * @return string
     */
    public function getLaserLength($direction) {
        return $this->lasers[$direction];
    }



    public function getGunDirections()
    {
        $guns = [];
        foreach ($this->getGuns() as $gunDirection) {
            $guns[] = 'gun_' . $gunDirection;
        }

        return implode(' ', $guns);
    }

    /**
     * @param $laserType
     *
     * @return bool
     */
    public function isPassableForLaser($laserType)
    {
        return false;
    }

}