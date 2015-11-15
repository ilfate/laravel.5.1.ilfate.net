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
class CrewMember
{
    /**
     * @var Ship
     */
    protected $ship;

    protected $id;
    protected $x;
    protected $y;
    protected $d;

    use Exportable;

    /**
     * @return array
     */
    static function getExportList()
    {
        return [
            'id',
            'x',
            'y',
            'd'
        ];
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


}