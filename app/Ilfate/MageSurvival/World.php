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
    protected $data;
    protected $unitsInited;
    protected $objectsInited;
    protected $type;
    protected $events = [];
    protected $worldConfig = [];
    protected $turn;

    protected $isWorldChanged = false;
    protected $deleted = false;

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
        $this->data = json_decode($mageWorld->data, true);
        $this->type = $mageWorld->type;
        $this->turn = $mageWorld->turn;
        $this->worldConfig = \Config::get('mageSurvival.worlds.' . $this->type);

        Event::import(json_decode($mageWorld->events, true));
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
        if ($this->deleted) {
            return; 
        }
        $this->mageWorldEntity->map     = json_encode($this->map);
        $this->mageWorldEntity->objects = json_encode($this->objects);
        $this->mageWorldEntity->units   = json_encode($this->units);
        $this->mageWorldEntity->data    = json_encode($this->data);
        $this->mageWorldEntity->turn    = $this->turn;
        $this->mageWorldEntity->save();
    }

    public function addRandomObject($x, $y)
    {
        if (!empty($this->objects[$y][$x])) return ;

        $object = MapObject::getRandomObject($x, $y, $this);
        if (!$object) return ;
        $this->objects[$y][$x] = $object->export();
    }

    public function addObject($objectType, $x, $y)
    {
        if ($this->getObject($x, $y)) {
            return null;
        }
        $object = MapObject::getObject($x, $y, $objectType, $this);
        $this->objects[$y][$x] = $object->export();
        $this->update();
        return $object;
    }

    public function addUnit($unitType, $x, $y, $stage = false)
    {
        if ($this->getUnit($x, $y)) {
            return null;
        }
        $unit = Unit::getUnit($x, $y, $unitType, $this, GameBuilder::getGame()->getMage());
        $this->units[$y][$x] = $unit->export();
        $this->update();
        if ($stage) {
            $mage = GameBuilder::getGame()->getMage();
            $targetX = $x - $mage->getX();
            $targetY = $y - $mage->getY();
            if (abs($targetX) <= 5 && abs($targetY) <= 5) {
                GameBuilder::animateEvent(Game::EVENT_NAME_ADD_UNIT,
                    ['unit'    => $unit->exportForView(),
                     'targetX' => $x - $mage->getX(),
                     'targetY' => $y - $mage->getY(),
                    ],
                    $stage);
            }
        }
        return $unit;
    }

    public function addRandomUnit($x, $y)
    {
        if (!empty($this->units[$y][$x])) return ;

        $unit = Unit::getRandomUnit($x, $y, $this, $this->getGame()->getMage());
        if (!$unit) return ;
        $this->units[$y][$x] = $unit->export();
    }

    public function getObject($x, $y)
    {
        if (!empty($this->objectsInited[$y][$x])) {
            return $this->objectsInited[$y][$x];
        }
        if (empty($this->objects[$y][$x])) {
            return null;
        }
        $objectData = $this->objects[$y][$x];
        $this->objectsInited[$y][$x] = MapObject::createObjectFromData($this, $x, $y, $objectData);
        return $this->objectsInited[$y][$x];
    }

    public function deleteObject($x, $y)
    {
        if (empty($this->objects[$y][$x])) {
            return null;
        }
        unset($this->objectsInited[$y][$x]);
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

    /**
     * @param      $x
     * @param      $y
     * @param      $range
     * @param bool $team
     *
     * @return Unit[]
     */
    public function getUnitsAround($x, $y, $range, $teams = [])
    {
        $units = [];
        $xStart = $x - $range;
        $yStart = $y - $range;
        $xEnd = $x + $range;
        $yEnd = $y + $range;
        for($iy = $yStart; $iy <= $yEnd; $iy++) {
            for($ix = $xStart; $ix <= $xEnd; $ix++) {
                $unit = $this->getUnit($ix, $iy);
                if ($unit) {
                    if (!$teams || in_array($unit->getTeam(), $teams)) {
                        $units[] = $unit;
                    }
                }
            }
        }
        return $units;
    }

    public function getNearestTargetByTeam($team, $fromX, $fromY, $limit)
    {
        $range = 1;
        while ($range <= $limit) {
            $units = [];
            for ($i = -$range; $i < $range; $i++) {
                $cells[0] = [$i, -$range];
                $cells[3] = [-$range, -$i];
                $cells[2] = [-$i, $range];
                $cells[1] = [$range, $i];
                for ($d = 0; $d <= 3; $d++) {
                    $unit = $this->getUnit($cells[$d][0] + $fromX, $cells[$d][1] + $fromY);
                    if ($unit && $unit->getTeam() == $team) {
                        $units[] = $unit;
                    }
                }
            }
            if ($units) {
                return ChanceHelper::oneFromArray($units);
            }
            $range++;
        }
        return false;
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

    public function moveObject($fromX, $fromY, $toX, $toY)
    {
        $object = $this->getObject($fromX, $fromY);
        if (!empty($this->objects[$toY][$toX]) || !empty($this->objectsInited[$toY][$toX])) {
            throw new \Exception('We are trying to move object to occupied cell');
        }
        $this->objectsInited[$toY][$toX] = $object;
        $this->objects[$toY][$toX] = $object->export();
        unset($this->objectsInited[$fromY][$fromX]);
        unset($this->objects[$fromY][$fromX]);
        $this->update();
    }

    public function destroyUnit($x, $y, $id)
    {
        if (empty($this->units[$y][$x])) {
            throw new \Exception('we cant destroy unit if there is no units here');
        }
        unset($this->units[$y][$x]);
        unset($this->unitsInited[$y][$x]);
        Event::removeEventsRelatedToUnit($id);
        $this->update();
    }

    public function updateUnit(Unit $unit)
    {
        if (!$unit->isAlive()) {
            return;
        }
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

    public function updateObject(MapObject $object)
    {
        $x = $object->getX();
        $y = $object->getY();
        if (empty($this->objects[$y][$x])) {
            throw new \Exception('We trying to update object, but there is no object here...');
        }
        $oldObject = $this->objects[$y][$x];
        if ($oldObject['id'] != $object->getId()) {
            throw new \Exception('Looks like we are updating object, but here we have another object id mismatch...');
        }
        $this->objects[$y][$x] = $object->export();
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

    public static function getRealDistance($unit1, $unit2)
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
        return sqrt(pow($x1 - $x2, 2) + pow($y1 - $y2, 2));
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
        if ($object = $this->getObject($x, $y)) {
            if (!$object->isPassable()) {
                return false;
            }
        }
        $mage = GameBuilder::getGame()->getMage();
        if ($mage->getX() == $x && $mage->getY() == $y) {
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
     * @param array $events
     */
    public function setEvents($events)
    {
        $this->mageWorldEntity->events = json_encode($events);
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

    public function getNextCoord($x, $y, $d)
    {
        switch ($d) {
            case 0: $y -= 1; break;
            case 1: $x += 1; break;
            case 2: $y += 1; break;
            case 3: $x -= 1; break;
        }
        return [$x, $y];
    }

    /**
     * @return array|mixed
     */
    public function getWorldConfig()
    {
        return $this->worldConfig;
    }

    /**
     * @return mixed
     */
    public function getTurn()
    {
        return $this->turn;
    }

    /**
     * @param mixed $value
     */
    public function increaseTurn($value = 1)
    {
        $this->turn += $value;
        $this->update();
    }

    public function destroy()
    {
        $this->deleted = true;
        $this->mageWorldEntity->delete();
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    public function deleteDataKey($key)
    {
        if (!empty($this->data[$key])) {
            unset($this->data[$key]);
        }
        $this->update();
    }

    public  function addData($key, $value)
    {
        $this->data[$key] = $value;
    }

}