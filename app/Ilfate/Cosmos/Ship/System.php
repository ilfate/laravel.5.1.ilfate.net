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
class System
{
    const CONFIG_DATA_CELLS = 'cells';

    protected $id;
    /**
     * @var Cell[]
     */
    protected $cells;
    /**
     * @var Ship
     */
    protected $ship;
    /**
     * @var Module
     */
    protected $module;
    /**
     * @var  CrewMember
     */
    protected $crewMember;
    protected $type;

    protected $x;
    protected $y;

    use Exportable;
    use Rotatable;

    /**
     * @return array
     */
    static function getExportList()
    {
        return [
            'type',
            'id',
            'x',
            'y',
            'd'
        ];
    }

    public function install()
    {
        $config = \Config::get('cosmos.game.ship.systems.' . $this->getType());
        if (!$config) {
            throw new \Exception('No config found for System type = ' . $this->getType());
        }
        foreach ($config['cells'] as $y => $row) {
            foreach ($row as $x => $cellArr) {
                list($shipX, $shipY) = $this->convertCoordinatesToShip($x, $y, $this->x, $this->y);
                $currentCell = $this->getShip()->getCell($shipX, $shipY);
                if ($currentCell && $currentCell->getSystem()) {
                    throw new \Exception('This cell is already occupied by a system');
                }
                switch ($cellArr['require']) {
                    case Cell::CELL_TYPE_NORMAL:
                        if (!$currentCell) {
                            throw new \Exception('We can`t install this part of a system in plain space');
                        }
                        break;
                    case Cell::CELL_TYPE_SPACE:
                        if ($currentCell && $currentCell->getType() == Cell::CELL_TYPE_NORMAL) {
                            throw new \Exception('We can`t install this part of a system inside of a module');
                        }
                        if (!$currentCell) {
                            $currentCell = new Cell($shipX, $shipY);
                            $currentCell->setType(Cell::CELL_TYPE_SPACE);
                            $this->getShip()->addCell($currentCell);
                        }
                        break;
                }

                $currentCell->setSystem($this);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Cell[]
     */
    public function getCells()
    {
        return $this->cells;
    }

    /**
     * @param Cell[] $cells
     */
    public function setCells($cells)
    {
        $this->cells = $cells;
    }

    /**
     * @return Ship
     */
    public function getShip()
    {
        return $this->ship;
    }

    /**
     * @param Ship $ship
     */
    public function setShip($ship)
    {
        $this->ship = $ship;
    }

    /**
     * @return Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param Module $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * @return CrewMember
     */
    public function getCrewMember()
    {
        return $this->crewMember;
    }

    /**
     * @param CrewMember $crewMember
     */
    public function setCrewMember($crewMember)
    {
        $this->crewMember = $crewMember;
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
    public function getD()
    {
        return $this->d;
    }

    /**
     * @param mixed $d
     */
    public function setD($d)
    {
        $this->d = $d;
    }
}