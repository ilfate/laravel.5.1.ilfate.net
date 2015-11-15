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
class Cell
{
    const CELL_TYPE_NORMAL = 'normal';
    const CELL_TYPE_SPACE = 'space';

    const CELL_DOOR_CLASS = 'module-door-';

    use \Ilfate\Cosmos\Ship\FrontEnd\Cell;

    protected $ship;
    protected $module;
    protected $system;
    protected $crewMember;

    protected $x;
    protected $y;
    protected $type;
    protected $passableDirections;
    protected $doors;

    public function __construct($x, $y)
    {
        $this->setX($x);
        $this->setY($y);
    }

    /**
     * @return mixed
     */
    public function getShip()
    {
        return $this->ship;
    }

    /**
     * @param mixed $ship
     */
    public function setShip($ship)
    {
        $this->ship = $ship;
    }

    /**
     * @return mixed
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param mixed $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * @return mixed
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * @param mixed $system
     */
    public function setSystem($system)
    {
        $this->system = $system;
    }

    /**
     * @return mixed
     */
    public function getCrewMember()
    {
        return $this->crewMember;
    }

    /**
     * @param mixed $crewMember
     */
    public function setCrewMember($crewMember)
    {
        $this->crewMember = $crewMember;
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
     * @return mixed
     */
    public function getPassableDirections()
    {
        return $this->passableDirections;
    }

    /**
     * @param mixed $passableDirections
     */
    public function setPassableDirections($passableDirections)
    {
        $this->passableDirections = $passableDirections;
    }

    /**
     * @return mixed
     */
    public function getDoors()
    {
        return $this->doors ?: [];
    }

    /**
     * @param mixed $doors
     */
    public function setDoors($doors)
    {
        $this->doors = $doors;
    }
}