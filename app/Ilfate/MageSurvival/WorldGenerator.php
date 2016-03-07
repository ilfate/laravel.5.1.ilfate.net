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

    protected $generatorConfig = [
        'spawnLocation' => [
            'radius' => 1,
        ],
    ];

    protected $visibleObjects = [];
    protected $visibleUnits = [];

    public function __construct(World $world, Mage $mage)
    {
        $this->config = \Config::get('mageSurvival');
        $this->world = $world;
        $this->mage = $mage;
    }

    /**
     * INit new world
     */
    public function init()
    {
        $map = [];
        //create spawn
        $radius = $this->generatorConfig['spawnLocation']['radius'];

        for ($y = -$radius; $y <= $radius; $y++) {
            for ($x = -$radius; $x <= $radius; $x++) {
                $map[$y][$x] = $this->getCellByType(self::CELL_TYPE_SPAWN);
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
        $this->world->setMap($map);
        $this->world->save();
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
                $objects[$y][$x] = $object->export();
            }
        }
        return $objects;
    }

    public function exportVisibleUnits()
    {
        $units = [];
        foreach ($this->visibleUnits as $y => $col) {
            foreach ($col as $x => $unit) {
                $units[$y][$x] = $unit->export();
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
        $map = $this->world->getMap();
        if (empty($map[$y][$x])) {
            $map[$y][$x] = $this->getCellByType(self::CELL_TYPE_RANDOM);
            $this->world->setMap($map);

            if (ChanceHelper::chance(10)) {
                // create object
                $this->world->addRandomObject($x, $y);
            }
            if (ChanceHelper::chance(15)) {
                $this->world->addRandomUnit($x, $y);
            }

            $this->world->update();

        }
        return $map[$y][$x];
    }

    /**
     * @param $type
     *
     * @return string
     */
    abstract public function getCellByType($type);

}