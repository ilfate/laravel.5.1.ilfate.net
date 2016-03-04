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
namespace Ilfate\MageSurvival;
use Ilfate\MageWorld;

/**
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
class World
{
    const WORLD_TYPE_TUTORIAL = 1;

    protected $map;
    protected $objects;
    protected $units;
    protected $type;

    protected $isWorldChanged = false;

    /**
     * @var MageWorld
     */
    private $mageWorldEntity;

    protected $game;

    public function __construct(MageWorld $mageWorld)
    {
        $this->config          = \Config::get('mageSurvival');
        $this->mageWorldEntity = $mageWorld;
        $this->map = json_decode($mageWorld->map, true);
        $this->objects = json_decode($mageWorld->objects, true);
        $this->units = json_decode($mageWorld->units, true);
        $this->type = $mageWorld->type;
    }

    public function update()
    {
        $this->isWorldChanged = true;
    }

    public function saveIfChanged()
    {
        if ($this->isWorldChanged) {
            $this->save();
        }
    }

    public function save()
    {
        $this->mageWorldEntity->map = json_encode($this->map);
        $this->mageWorldEntity->objects = json_encode($this->objects);
        $this->mageWorldEntity->units = json_encode($this->units);
        $this->mageWorldEntity->save();
    }

    public function addRandomObject($x, $y)
    {
        if (!empty($this->objects[$y][$x])) return false;

        $object = MapObject::getRandomObject($x, $y, $this);
        $this->objects[$y][$x] = $object->export();
    }

    public function getObject($x, $y)
    {
        if (empty($this->objects[$y][$x])) {
            return null;
        }
        $objectData = $this->objects[$y][$x];
        return MapObject::createObjectFromData($this, $x, $y, $objectData);
    }

    /**
     * @return mixed
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @param mixed $map
     */
    public function setMap($map)
    {
        $this->map = $map;
    }

    /**
     * @return mixed
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * @param mixed $objects
     */
    public function setObjects($objects)
    {
        $this->objects = $objects;
    }

    /**
     * @return mixed
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * @param mixed $units
     */
    public function setUnits($units)
    {
        $this->units = $units;
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
    public function getBioms()
    {
        return $this->bioms;
    }

    /**
     * @param mixed $bioms
     */
    public function setBioms($bioms)
    {
        $this->bioms = $bioms;
    }

    /**
     * @return mixed
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param mixed $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }
}