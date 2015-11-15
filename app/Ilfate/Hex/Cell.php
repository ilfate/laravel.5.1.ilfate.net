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
class Cell
{

    protected $x;
    protected $y;
    protected $type;
    protected $guns = [];
    protected $lasers = [];

    const HEX_SIDE = 3;

    const TYPE_GUN   = 'gun';
    const TYPE_CELL  = 'cell';
    const TYPE_WALL = 'wall';

    /**
     * @var Cell[]
     */
    protected $neighbors;

    /**
     * @var HexagonalField
     */
    protected $field;

    public function __construct(HexagonalField $field, $x, $y, $type)
    {
        $this->field = $field;
        $this->x = $x;
        $this->y = $y;
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function export()
    {
        return false;
    }

    public function import($data)
    {
        switch ($data['type']) {
            case self::TYPE_WALL:
                $wall = $this->makeAWall();
                $wall->import($data);
                break;
        }
    }

    /**
     * @param $direction
     *
     * @return bool|Cell
     */
    public function getNeighbor($direction)
    {
        return $this->field->getNeighborForCell($this->x, $this->y, $direction);
    }

    /**
     * @return mixed
     */
    public function getXCoordinate()
    {
        $size = $this->getX() * ($this->getHexWidth() + 0.2) + $this->getY() * ($this->getHexWidth() / 2 + 0.1);
        return $size + 0.2;
    }

    /**
     * @return mixed
     */
    public function getYCoordinate()
    {
        $size = $this->getY() * (self::HEX_SIDE * 2 * 3 / 4 + 0.2);
        return $size ;
    }

    /**
     * @return float
     */
    public function getHexWidth()
    {
        return round(self::HEX_SIDE * sqrt(3), 2);
    }

    /**
     * @param     $direction
     * @param int $pathLength
     *
     * @return int
     */
    public function getCellsLengthToObstacle($direction, $pathLength = 0)
    {
        $neighbor = $this->getNeighbor($direction);
        if ($neighbor) {
            if ($neighbor->getType() !== self::TYPE_CELL) {
                return $pathLength;
            }
            return $neighbor->getCellsLengthToObstacle($direction, $pathLength + 1);
        }
    }

    /**
     * @return Wall
     */
    public function makeAWall()
    {
        $wall = new Wall($this->field, $this->x, $this->y, self::TYPE_WALL);
        $this->field->setCell(
            $wall
        );
        return $wall;
    }

    /**
     * @param $cells
     *
     * @return string
     */
    public function cellsToEm($cells)
    {
        return (($cells + 0.5) * $this->getHexWidth()) + ($cells * 0.2) . 'em';
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getGuns()
    {
        return $this->guns;
    }

    /**
     * @param array $guns
     */
    public function setGuns($guns)
    {
        $this->guns = $guns;
    }

}