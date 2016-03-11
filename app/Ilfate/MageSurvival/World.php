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
    protected $unitsInited;
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

    public function getCell($x, $y)
    {
        if (isset($this->map[$y][$x])) {
            return $this->map[$y][$x];
        } else {
            return false;
        }
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
        if (!empty($this->objects[$y][$x])) return ;

        $object = MapObject::getRandomObject($x, $y, $this);
        $this->objects[$y][$x] = $object->export();
    }

    public function addRandomUnit($x, $y)
    {
        if (!empty($this->units[$y][$x])) return ;

        $unit = Unit::getRandomUnit($x, $y, $this, $this->getGame()->getMage());
        $this->units[$y][$x] = $unit->export();
    }

    public function getObject($x, $y)
    {
        if (empty($this->objects[$y][$x])) {
            return null;
        }
        $objectData = $this->objects[$y][$x];
        return MapObject::createObjectFromData($this, $x, $y, $objectData);
    }

    public function deleteObject($x, $y)
    {
        if (empty($this->objects[$y][$x])) {
            return null;
        }
        unset($this->objects[$y][$x]);
        $this->update();
    }

    /**
     * @param $x
     * @param $y
     *
     * @return Unit
     */
    public function getUnit($x, $y)
    {
        if (!empty($this->unitsInited[$y][$x])) {
            return $this->unitsInited[$y][$x];
        }
        if (empty($this->units[$y][$x])) {
            return null;
        }
        $unitData = $this->units[$y][$x];
        $this->unitsInited[$y][$x] = Unit::createUnitFromData($this, $this->getGame()->getMage(), $x, $y, $unitData);
        return $this->unitsInited[$y][$x];
    }

    public function moveUnit($fromX, $fromY, $toX, $toY)
    {
        $unit = $this->getUnit($fromX, $fromY);
        if (!empty($this->units[$toY][$toX]) || !empty($this->unitsInited[$toY][$toX])) {
            throw new \Exception('We are trying to move unit to occupied cell');
        }
        $this->unitsInited[$toY][$toX] = $unit;
        $this->units[$toY][$toX] = $unit->export();
        unset($this->unitsInited[$fromY][$fromX]);
        unset($this->units[$fromY][$fromX]);
        $this->update();

    }

    public function destroyUnit($x, $y)
    {
        if (empty($this->units[$y][$x])) {
            throw new \Exception('we cant destroy unit if there is no units here');
        }
        unset($this->units[$y][$x]);
        unset($this->unitsInited[$y][$x]);
        $this->update();
    }

    public function updateUnit(Unit $unit)
    {
        $x = $unit->getX();
        $y = $unit->getY();
        if (empty($this->units[$y][$x])) {
            throw new \Exception('We trying to update unit, but there is no unit here...');
        }
        $oldUnit = $this->units[$y][$x];
        if ($oldUnit['id'] != $unit->getId()) {
            throw new \Exception('Look like we are updating unit, but here we have another unit is World...');
        }
        $this->units[$y][$x] = $unit->export();
        $this->update();
    }


    public function getNextMoveToGetTo($from, $to)
    {
        $distances = [];
        $cells = [];
        for($d = 0; $d < 4; $d++) {
            switch($d) {
                case 0: $x = $from[0]; $y = $from[1] - 1; break;
                case 1: $x = $from[0] + 1; $y = $from[1]; break;
                case 2: $x = $from[0]; $y = $from[1] + 1; break;
                case 3: $x = $from[0] - 1; $y = $from[1]; break;
            }
            $distances[$d] = self::getDistance([$x, $y], $to);
            $cells[$d] = [$x, $y];
        }

        // we need next logic to not let unit go in opposite direction
        if ($from[0] == $to[0]) {
            if ($from[1] > $to[1]) {
                $distances[2] += 0.5;
            } else {
                $distances[0] += 0.5;
            }
        } else if ($from[1] == $to[1]) {
            if ($from[0] > $to[0]) {
                $distances[3] += 0.5;
            } else {
                $distances[1] += 0.5;
            }
        }

        asort($distances);
        $shortestDirections = [];
        $shortestDistance = 0;
        foreach($distances as $d => $distance) {
            if ($shortestDirections && $shortestDistance != $distance) {
                break;
            }
            if ($this->isPassable($cells[$d][0], $cells[$d][1])) {
                $shortestDirections[] = [$d, $cells[$d][0], $cells[$d][1]];
                $shortestDistance = $distance;
            }
        }
        if (count($shortestDirections) > 0) {
            return ChanceHelper::oneFromArray($shortestDirections);
        } else {
            return false;
        }
    }

    public static function getDistance($unit1, $unit2)
    {
        if (is_object($unit1)) {
            $x1 = $unit1->getX();
            $y1 = $unit1->getY();
        } else {
            $x1 = $unit1[0];
            $y1 = $unit1[1];
        }
        if (is_object($unit2)) {
            $x2 = $unit2->getX();
            $y2 = $unit2->getY();
        } else {
            $x2 = $unit2[0];
            $y2 = $unit2[1];
        }
        return abs($x1 - $x2) + abs($y1 - $y2);
    }

    public function isPassable($x, $y)
    {
        $cell = $this->getCell($x, $y);
        if (!GameBuilder::getGame()->getWorldGenerator()->isPassable($cell)) {
            return false;
        }
        if ($this->getUnit($x, $y)) {
            return false;
        }
        return true;
    }

    public function isOutSideOfViewArea($x, $y, Mage $mage)
    {
        $centerX = $mage->getX();
        $centerY = $mage->getY();
        $radius = $this->config['game']['screen-radius'];

        if (abs($centerX - $x) > $radius || abs($centerY - $y) > $radius) {
            return true;
        }
        return false;
    }

    public static function isNeighbours($x1, $y1, $x2, $y2)
    {
        if (abs($x1 - $x2) > 1 || abs($y1 - $y2) > 1) {
            return false;
        }
        return true;
    }

    public function setCell($x, $y, $cell)
    {
        $this->map[$y][$x] = $cell;
        $this->update();
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
     * @return Game
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