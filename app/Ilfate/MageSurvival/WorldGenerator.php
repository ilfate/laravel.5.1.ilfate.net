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
        'portalLocation' => ['x' => 0, 'y' => 1]
    ];

    protected $visibleObjects = [];
    protected $visibleUnits = [];
    protected $notPassable = [];

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
                    if (strpos($cellData, '-') !== false) {
                        list($cell, $unitId) = explode('-', $cellData);
                        $this->world->addUnit($unitId, $x, $y);
                    } else if (strpos($cellData, '+') !== false) {
                        list($cell, $objectId) = explode('+', $cellData);
                        $this->world->addObject($objectId, $x, $y);
                    } else {
                        $cell = $cellData;
                    }
                    $map[$y][$x] = $cell;
                }
            }
            unset($tempMap);
        } else {
            $radius         = static::$generatorConfig['spawnLocation']['radius'];
            $portalLocation = static::$generatorConfig['portalLocation'];

            for ($y = -$radius; $y <= $radius; $y++) {
                for ($x = -$radius; $x <= $radius; $x++) {
                    $map[$y][$x] = $this->getCellByType(self::CELL_TYPE_SPAWN);
                    if ($x == $portalLocation['x'] && $y == $portalLocation['y']) {
                        $this->world->addObject(1000, $x, $y);
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
        $this->world->setMap($map);
        $this->world->save();
        $this->mage->setX(0);
        $this->mage->setY(0);
        $this->mage->save();
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
        $activeUnits = [];
        $centerX = $mage->getX();
        $centerY = $mage->getY();
        $radius = $this->config['game']['active-units-radius'];
        $map = [];
        for ($y = -$radius; $y <= $radius; $y++) {
            for ($x = -$radius; $x <= $radius; $x++) {
                $dX = $centerX + $x;
                $dY = $centerY + $y;
                if ($unit = $this->world->getUnit($dX, $dY)) {
                    $activeUnits[] = $unit;
                }
            }
        }
        return $activeUnits;
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
            $cell = $this->getCellByType(self::CELL_TYPE_RANDOM);
            $this->world->setCell($x, $y, $cell);

            if ($this->world->isPassable($x, $y)) {
                if (ChanceHelper::chance(8)) {
                    // create object
                    $this->world->addRandomObject($x, $y);
                }
                if (ChanceHelper::chance(3)) {
                    $this->world->addRandomUnit($x, $y);
                }
            }
        }
        return $cell;
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
    abstract public function getCellByType($type);



}