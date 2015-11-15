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
                        $length = $this->getCellsLengthToObstacle($oppositeDirection);
                        $this->lasers[$oppositeDirection] = $length;
                    }
                }
            }
            $this->setGuns($result);
        }
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
                    $length = $this->getCellsLengthToObstacle($oppositeDirection);
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

}