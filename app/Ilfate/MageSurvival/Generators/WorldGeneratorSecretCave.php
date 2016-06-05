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
namespace Ilfate\MageSurvival\Generators;
use Ilfate\Geometry2DCells;
use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Spell;
use Ilfate\MageSurvival\WorldGenerator;

/**
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
class WorldGeneratorSecretCave extends WorldGeneratorWitchForest
{

    protected $walls = [
        self::CELL_CAVE,
        self::CELL_CAVE_2,
        self::CELL_CAVE_3,
    ];

    protected $random = [
        self::CELL_CAVE_FLOOR,
        self::CELL_CAVE_FLOOR,
        self::CELL_CAVE_FLOOR,
        self::CELL_CAVE_FLOOR,
        self::CELL_CAVE_FLOOR,
        self::CELL_CAVE_FLOOR_2,
        self::CELL_CAVE_FLOOR_2,
        self::CELL_CAVE_FLOOR_2,
        self::CELL_STONE_2,
        self::CELL_STONE_2,
    ];
   

    protected static $generatorConfig = [
        'dialog' => [
//            'help' => [
//                ['method' => 'whereIsWitch']
//            ], 'lore' => [
//                ['message' => 'I`m not sure who build those portals. They are connecting different parts of the world as well as different worlds.'],
//                ['message' => 'My school of magic was not the only one. I heard that there was at least 5 other schools of magic.'],
//
//            ], 'joke' => [
//                ['message' => 'Am I the only one here who thinks that those spider are way too big?'],
//                ['message' => 'I should have remembered ingredients for that awesome spell...'],
//
//            ], 'turn' => [
//                1 => ['message' => 'I have no idea what happened to my school. I hope this witch have some answers.'],
//                3 => ['message' => 'I heard that she lives in some kind of house.'],
//                5 => ['message' => 'She was teaching dark art in my school bank in the days.'],
//                7 => ['message' => 'Let`s hope she still have her senses.'],
//                9 => ['method' => 'whereIsWitch'],
//                11 => ['message' => 'Maybe I need to create more spells?.'],
//                15 => ['message' => 'I hoped this forest is more like a park.'],
//                //100 => ['message' => 'I h.'],
//            ]
        ],
        'mapDistance' => 10,
    ];

    public function addAdditionalToMap(array &$map)
    {
        $newMap = $this->addLocation(0, -self::$generatorConfig['mapDistance'], LocationsForest::$spiderNest, $map, 0);

        $boss = null;
        $units = $this->world->getUnits();
        foreach ($units as $y => $row) {
            foreach ($row as $x => $unit) {
                if ($unit['type'] == 101) {
                    // this is witch
                    $boss = $this->world->getUnit($x, $y);
                }
            }
        }
        //$this->world->addData('bossLocation', [$boss->getX(), $boss->getY()]);
        
        Event::create(Event::EVENT_UNIT_BEFORE_DYING, [
                'times' => 1,
                'owner' => $boss,
                'loot' => 1000, 'isForced' => true
            ],
            'Objects:createLoot'
        );
        $this->world->setEvents(Event::export());
        $this->world->save();
        $map = $newMap;
    }

    /**
     * @param $type
     *
     * @return string
     * @throws \Exception
     */
    public function getCellByType($type, $x, $y)
    {
        $cell = '';
        switch($type) {
            case WorldGenerator::CELL_TYPE_SPAWN:
                $cell = self::CELL_BURNT_LANDING;
                break;
            case WorldGenerator::CELL_TYPE_RANDOM:
                if ($y < 3 && $y > -(self::$generatorConfig['mapDistance'] + 2) && abs($x) <= 1) {
                    return ChanceHelper::oneFromArray([self::CELL_ROAD, self::CELL_ROAD_3, self::CELL_ROAD_4]);
                }
                $cell = $this->random[array_rand($this->random)];
                break;
            default:
                throw new \Exception('In ' . __CLASS__ . ' cell for type "' . $type . '" is not defined');
        }
        return $cell;
    }

    public function isWallReached($x, $y)
    {
        if ($y > 4 || $y < - (self::$generatorConfig['mapDistance'] + 3)) {
            return true;
        }
        if (abs($x) > 0) { return true; }
        return false;
    }

    public function wallChance($x, $y)
    {
        //$limit = static::$generatorConfig['mapLimit'];
        if ($y > 4 || $y < - (self::$generatorConfig['mapDistance'] + 5)) {
            return true;
        }
        
        if (abs($x) > 5) { return true; }
        if (abs($x) > 4) { return ChanceHelper::chance(50); }
        if (abs($x) > 3) { return ChanceHelper::chance(25); }
        if (abs($x) > 2) { return ChanceHelper::chance(12); }
        if (abs($x) > 1) { return ChanceHelper::chance(6); }
        if (abs($x) > 0) { return ChanceHelper::chance(3); }
        return false;
    }

    public function getUnitCreatingChance($x, $y)
    {
        return 5;
    }

    public function getObjectCreatingChance($x, $y)
    {
        return 0;
    }

}