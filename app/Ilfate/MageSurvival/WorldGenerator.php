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
use Ilfate\Geometry2DCells;

/**
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
abstract class WorldGenerator
{
    const CELL_TYPE_SPAWN = 'spawn';
    const CELL_TYPE_RANDOM = 'random';

    /**
     * @var World
     */
    protected $world;
    /**
     * @var Mage
     */
    protected $mage;

    protected $config;

    protected static $generatorConfig = [
        'spawnLocation' => [
            'radius' => 1,
        ],
        'mapLimit' => 50,
        'portalLocation' => ['x' => 0, 'y' => 1]
    ];
    protected $walls = [];
    protected $destroyable = [];
    protected $water = [];

    protected $visibleObjects = [];
    protected $visibleUnits = [];
    protected $notPassable = [];
    protected $activeUnits = [];
    protected $activeObjects = [];

    public function __construct(World $world, Mage $mage)
    {
        $this->config = \Config::get('mageSurvival');
        $this->world = $world;
        $this->mage = $mage;
    }

    /**
     * @return array
     */
    public static function getGeneratorConfig()
    {
        return static::$generatorConfig;
    }

    /**
     * INit new world
     */
    public function init()
    {
        $map = [];
        //create spawn
        if (!empty(static::$generatorConfig['world-predefined'])) {
            $tempMap = static::$generatorConfig['full-world'];
            foreach ($tempMap as $y => $row) {
                foreach ($row as $x => $cellData) {
                    $cell = $this->processCellData($cellData, $x, $y);
                    $map[$y][$x] = $cell;
                }
            }
            unset($tempMap);
        } else {
            if (!empty( static::$generatorConfig['spawnLocation']['radius'])) {
                $radius         = static::$generatorConfig['spawnLocation']['radius'];
                $portalLocation = static::$generatorConfig['portalLocation'];

                for ($y = -$radius; $y <= $radius; $y++) {
                    for ($x = -$radius; $x <= $radius; $x++) {
                        $map[$y][$x] = $this->getCellByType(self::CELL_TYPE_SPAWN, $x, $y);
                        if ($x == $portalLocation['x'] && $y == $portalLocation['y']) {
                            $this->world->addObject(1000, $x, $y);
                        }
                    }
                }
            }
            // generate cell for rest of the screen
            $radius = $this->config['game']['screen-radius'];
            for ($y = -$radius; $y <= $radius; $y++) {
                for ($x = -$radius; $x <= $radius; $x++) {
                    if (!isset($map[$y][$x])) {
                        $map[$y][$x] = $this->getOrGenerateCell($x, $y);
                    }
                }
            }
        }
        $this->addAdditionalToMap($map);
        $this->world->setMap($map);
        $this->world->save();
        $this->mage->setX(0);
        $this->mage->setY(0);
        $this->mage->save();
    }

    public function processCellData($cellData, $x, $y)
    {
        if (strpos($cellData, '-') !== false) {
            list($cell, $unitId) = explode('-', $cellData);
            $this->world->addUnit($unitId, $x, $y);
        } else if (strpos($cellData, '+') !== false) {
            list($cell, $objectId) = explode('+', $cellData);
            $this->world->addObject($objectId, $x, $y);
        } else {
            $cell = $cellData;
        }
        return $cell;
    }

    public function mageEnter()
    {
        $this->mage->setX(0);
        $this->mage->setY(0);
        $this->mage->save();
    }

    public function getScreenRadius()
    {
        return $this->config['game']['screen-radius'];
    }

    public function exportMapForView(Mage $mage)
    {
        $centerX = $mage->getX();
        $centerY = $mage->getY();
        $radius = $this->config['game']['screen-radius'];
        $map = [];
        for ($y = -$radius; $y <= $radius; $y++) {
            for ($x = -$radius; $x <= $radius; $x++) {
                $dX = $centerX + $x;
                $dY = $centerY + $y;
                $map[$y][$x] = $this->getOrGenerateCell($dX, $dY);
                if ($object = $this->world->getObject($dX, $dY)) {
                    $this->visibleObjects[$y][$x] = $object;
                }
                if ($unit = $this->world->getUnit($dX, $dY)) {
                    $this->visibleUnits[$y][$x] = $unit;
                }
            }
        }
        $this->world->saveIfChanged();
        return $map;
    }

    public function getActiveUnits(Mage $mage)
    {
        if (!$this->activeUnits) {
            $this->getActiveUnitsAndObjects($mage);
        }
        return $this->activeUnits;
    }
    public function getActiveObjects(Mage $mage)
    {
        if (!$this->activeObjects) {
            $this->getActiveUnitsAndObjects($mage);
        }
        return $this->activeObjects;
    }

    public function getActiveUnitsAndObjects(Mage $mage)
    {
        if (!$this->activeUnits && !$this->activeObjects) {
            $centerX             = $mage->getX();
            $centerY             = $mage->getY();
            $this->activeUnits   = [];
            $this->activeObjects = [];
            $radius              = $this->config['game']['active-units-radius'];
            for ($y = -$radius; $y <= $radius; $y++) {
                for ($x = -$radius; $x <= $radius; $x++) {
                    $dX = $centerX + $x;
                    $dY = $centerY + $y;
                    if ($unit = $this->world->getUnit($dX, $dY)) {
                        $this->activeUnits[] = $unit;
                    }
                    if ($object = $this->world->getObject($dX, $dY)) {
                        $this->activeObjects[] = $object;
                    }
                }
            }
        }
        return ['units' => $this->activeUnits, 'objects' => $this->activeObjects];
    }

    public function getVisibleUnits(Mage $mage)
    {
        $visible = [];
        $centerX = $mage->getX();
        $centerY = $mage->getY();
        $radius = $this->config['game']['screen-radius'];
        for ($y = -$radius; $y <= $radius; $y++) {
            for ($x = -$radius; $x <= $radius; $x++) {
                $dX = $centerX + $x;
                $dY = $centerY + $y;
                if ($unit = $this->world->getUnit($dX, $dY)) {
                    $visible[] = $unit;
                }
            }
        }
        return $visible;
    }

    public function exportVisibleObjects()
    {
        $objects = [];
        foreach ($this->visibleObjects as $y => $col) {
            foreach ($col as $x => $object) {
                $objects[$y][$x] = $object->exportForView();
            }
        }
        return $objects;
    }

    public function exportVisibleUnits()
    {
        $units = [];
        foreach ($this->visibleUnits as $y => $col) {
            foreach ($col as $x => $unit) {
                $units[$y][$x] = $unit->exportForView();
            }
        }
        return $units;
    }

    public function fillEmptyMap(&$map, Mage $mage)
    {
        $newMap = [];
        foreach ($map as $y => $col) {
            foreach ($col as $x => $value) {
                $dY = $y - $mage->getY();
                $dX = $x - $mage->getX();
                $newMap[$dY][$dX] = $this->getOrGenerateCell($x, $y);
                if ($object = $this->world->getObject($x, $y)) {
                    $this->visibleObjects[$dY][$dX] = $object;
                }
            }
        }
        $map = $newMap;
        $this->world->saveIfChanged();
    }

    public function getOrGenerateCell($x, $y)
    {
        $cell = $this->world->getCell($x, $y);
        if ($cell === false) {
            if ($this->isWallReached($x, $y) && $this->wallChance($x, $y)) {
                $cell = $this->getWall();
            } else {
                $cell = $this->getCellByType(self::CELL_TYPE_RANDOM, $x, $y);
            }
            $this->world->setCell($x, $y, $cell);

            if ($this->world->isPassable($x, $y)) {
                if (ChanceHelper::chance($this->getObjectCreatingChance($x, $y))) {
                    // create object
                    $this->world->addRandomObject($x, $y);
                }
                if (ChanceHelper::chance($this->getUnitCreatingChance($x, $y))) {
                    $this->world->addRandomUnit($x, $y);
                }
            }
        }
        return $cell;
    }

    public function getUnitCreatingChance($x, $y)
    {
        return 3;
    }

    public function getObjectCreatingChance($x, $y)
    {
        return 8;
    }

    public function getCellDestroyableBySource($x, $y, $source)
    {
        if (!empty($this->destroyable[$source])) {
            $cell = $this->world->getCell($x, $y);
            if (!empty($this->destroyable[$source][$cell])) {
                return $this->destroyable[$source][$cell];
            }
        }
        return false;
    }

    public function addLocation($x, $y, array $location, array $map, $flipDirection = 0, $isForced = false)
    {
        $newMap = $map;
        $flip = function($x, $y) { return [$x, $y]; };
        if ($flipDirection) {
            switch ($flipDirection) {
                case 1: $flip = function($x, $y) { return [-$y, $x]; }; break;
                case 2: $flip = function($x, $y) { return [-$x, -$y]; }; break;
                case 3: $flip = function($x, $y) { return [$y, -$x]; }; break;
            }
        }
        foreach ($location as $locationY => $row) {
            foreach ($row as $locationX => $cell) {
                list($locX, $locY) = $flip($locationX, $locationY);
                $dx = $x + $locX;
                $dy = $y + $locY;
                if (isset($newMap[$dy][$dx]) && !$isForced) {
                    return $map;
                }
                $cell = $this->processCellData($cell, $dx, $dy);
                $newMap[$dy][$dx] = $cell;
            }
        }
        return $newMap;
    }

    public function isWallReached($x, $y)
    {
        if (empty(static::$generatorConfig['mapLimit'])) {
            return false;
        }
        $limit = static::$generatorConfig['mapLimit'];
        if (abs($x) > $limit || abs($y) > $limit) {
            return true;
        }
        return false;
    }

    public function wallChance($x, $y)
    {
        $limit = static::$generatorConfig['mapLimit'];
        if (abs($x) > $limit &&  abs($y) > $limit) {
            // both are more
            return true;
        } else if (abs($x) > $limit) {
            if (abs($x) > $limit + 10) {
                return true;
            } else {
                if (ChanceHelper::chance((abs($x) - $limit) * 10)) {
                    return true;
                }
                return false;
            }
        } else if ( abs($y) > $limit) {
            if (abs($y) > $limit + 10) {
                return true;
            } else {
                if (ChanceHelper::chance((abs($y) - $limit) * 10)) {
                    return true;
                }
                return false;
            }
        } else {
            return false;
        }
    }

    public function getWall()
    {
        return ChanceHelper::oneFromArray($this->walls);
    }

    /**
     * @param $cell
     *
     * @return bool
     */
    public function isPassable($cell)
    {
        return !in_array($cell, $this->notPassable);
    }

    /**
     * @param $type
     *
     * @return string
     */
    abstract public function getCellByType($type, $x, $y);

    /**
     * @param array $map
     *
     * @return mixed
     */
    public function addAdditionalToMap(array &$map)
    {
       // nothing by default
    }

    public function isWater($cell)
    {
        return in_array($cell, $this->water);
    }
    
    public function worldTips($turn)
    {
        if (!empty(static::$generatorConfig['dialog'])) {
            if (!empty(static::$generatorConfig['dialog']['turn'][$turn])) {
                $messageConfig = static::$generatorConfig['dialog']['turn'][$turn];
                $this->mage->say($this->processConfigMessage($messageConfig));
                return ;
            }
        }
        if (ChanceHelper::chance(10)) {
            // lets show some thing.
            $massegesTypes = ['help', 'help', 'help', 'lore',' joke'];
            $type = ChanceHelper::oneFromArray($massegesTypes);
            if (!empty(static::$generatorConfig['dialog'][$type])) {
                $messageConfig = ChanceHelper::oneFromArray(static::$generatorConfig['dialog'][$type]);
                $this->mage->say($this->processConfigMessage($messageConfig));
                return ;
            }
        }    
    }

    public function processConfigMessage($config)
    {
        if (!empty($config['message'])) {
            return $config['message'];
        } else if (!empty($config['method'])) {
            return $this->{$config['method']}();
        }
    }
    
    public function onLeaveWorld()
    {
        
    }

    public function coordinatsToDirection($x1, $y1, $x2, $y2)
    {
        $calculation = Geometry2DCells::getAngleBetween2Dots($x1, $y1, $x2, $y2);
        $area = floor($calculation[1] / 22.5);
        switch ($area) {
            case 0:
            case 15:
                return 'east';
            case 1:
            case 2:
                return 'south-east';
            case 3:
            case 4:
                return 'south';
            case 5:
            case 6:
                return 'south-west';
            case 7:
            case 8:
                return 'west';
            case 9:
            case 10:
                return 'north-west';
            case 11:
            case 12:
                return 'north';
            case 13:
            case 14:
                return 'north-east';
        }
    }

}