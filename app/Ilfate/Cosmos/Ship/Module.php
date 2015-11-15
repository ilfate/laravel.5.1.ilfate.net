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
class Module
{
    const DEFAULT_CELL_VOLUME = 10;
    const DEFAULT_ENERGY_USE = 1;

    const CONFIG_DATA_CELLS = 'cells';
    const CONFIG_DATA_CELL_TYPE = 'type';
    const CONFIG_DATA_CELL_PASSABLE_DIRECTIONS = 'passableDirections';
    const CONFIG_DATA_CELL_DOORS = 'doors';

    const CONFIG_DATA_VOLUME = 'volume';
    const CONFIG_DATA_ENERGY_USE = 'energyUse';

    /**
     * @var Ship
     */
    protected $ship;
    /**
     * @var Cell[]
     */
    protected $cells;
    /**
     * @var System[]
     */
    protected $systems;
    /**
     * @var CrewMember[]
     */
    protected $crewMembers;

    protected $id;
    protected $x;
    protected $y;
    protected $type;
    protected $config;

    use Exportable;
    use Rotatable;
    use \Ilfate\Cosmos\Ship\FrontEnd\Module;

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

    /**
     * @throws \Exception
     */
    public function install()
    {
        $this->config = \Config::get('cosmos.game.ship.modules.' . $this->getType());
        if (!$this->config) {
            throw new \Exception('No config found for Module type = ' . $this->getType());
        }
        foreach ($this->config[self::CONFIG_DATA_CELLS] as $y => $row) {
            foreach ($row as $x => $cellArr) {
                list($shipX, $shipY) = $this->convertCoordinatesToShip($x, $y, $this->x, $this->y);
                if ($this->getShip()) {
                    $currentCell = $this->getShip()->getCell($shipX, $shipY);
                    if ($currentCell) {
                        throw new \Exception('We cant install module to an occupied cell');
                    }
                }
                $cell = new Cell($shipX, $shipY);
                $cell->setModule($this);
                $cell->setType(Cell::CELL_TYPE_NORMAL);
                if (!empty($cellArr[self::CONFIG_DATA_CELL_TYPE])) {
                    $cell->setType($cellArr[self::CONFIG_DATA_CELL_TYPE]);
                }
                if (!empty($cellArr[self::CONFIG_DATA_CELL_PASSABLE_DIRECTIONS])) {
                    $directions = $this->convertDirectionsToShip($cellArr[self::CONFIG_DATA_CELL_PASSABLE_DIRECTIONS]);
                    $cell->setPassableDirections($directions);
                }
                if (!empty($cellArr[self::CONFIG_DATA_CELL_DOORS])) {
                    $doors = $this->convertDirectionsToShip($cellArr[self::CONFIG_DATA_CELL_DOORS]);
                    $cell->setDoors($doors);
                }
                $this->addCell($cell);
                if ($this->getShip()) {
                    $this->getShip()->addCell($cell);
                }
            }
        }
    }

    /**
     * @return int
     */
    public function getVolume()
    {
        if (!empty($this->config[self::CONFIG_DATA_VOLUME])) {
            return $this->config[self::CONFIG_DATA_VOLUME];
        }
        return count($this->getCells()) * self::DEFAULT_CELL_VOLUME;
    }

    /**
     * @return int
     */
    public function getEnergyUse()
    {
        if (!empty($this->config[self::CONFIG_DATA_ENERGY_USE])) {
            return $this->config[self::CONFIG_DATA_ENERGY_USE];
        }
        return self::DEFAULT_ENERGY_USE;
    }

    /**
     * @param Cell $cell
     */
    public function addCell(Cell $cell)
    {
        $this->cells[] = $cell;
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
     * @return System[]
     */
    public function getSystems()
    {
        return $this->systems;
    }

    /**
     * @param System[] $systems
     */
    public function setSystems($systems)
    {
        $this->systems = $systems;
    }

    /**
     * @return CrewMember[]
     */
    public function getCrewMembers()
    {
        return $this->crewMembers;
    }

    /**
     * @param CrewMember[] $crewMembers
     */
    public function setCrewMembers($crewMembers)
    {
        $this->crewMembers = $crewMembers;
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
}