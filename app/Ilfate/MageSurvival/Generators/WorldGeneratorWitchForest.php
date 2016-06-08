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
use Ilfate\MageSurvival\Game;
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
class WorldGeneratorWitchForest extends WorldGenerator
{
    const CELL_BURNT_LANDING = 'bl';
    const CELL_FIELD_1 = 'f1';
    const CELL_FIELD_2 = 'f2';
    const CELL_FIELD_3 = 'f3';
    const CELL_FLOOR = 'f4';
    const CELL_WALL = 'w1';
    const CELL_WALL_2 = 'w2';
    const CELL_STONE = 's';
    const CELL_STONE_2 = 's1';
    const CELL_CAVE = 'c';
    const CELL_CAVE_2 = 'cc';
    const CELL_CAVE_3 = 'cC';
    const CELL_TREE_PINE = 't1';
    const CELL_TREE_PINE_2 = 't2';
    const CELL_TREE_PINE_3 = 't3';
    const CELL_TREE_OAK = 't4';
    const CELL_FOREST = 't0';
    const CELL_FOREST_2 = 'tf';
    const CELL_FOREST_3 = 'tF';
    const CELL_RIVER = 'r1';
    const CELL_ROAD = 'r2';
    const CELL_ROAD_3 = 'r3';
    const CELL_ROAD_4    = 'r4';
    const CELL_CAVE_FLOOR = 'c1';
    const CELL_CAVE_FLOOR_2 = 'c2';

    protected $cells = [
        self::CELL_BURNT_LANDING, // birnedLanding
    ];

    protected $walls = [
        self::CELL_FOREST,
        self::CELL_FOREST,
        self::CELL_FOREST,
        self::CELL_FOREST_2,
        self::CELL_FOREST_3,
    ];

    protected $random = [
        self::CELL_FIELD_1,
        self::CELL_FIELD_1,
        self::CELL_FIELD_1,
        self::CELL_FIELD_1,
        self::CELL_FIELD_1,
        self::CELL_FIELD_2,
        self::CELL_FIELD_3,
        self::CELL_TREE_PINE,
        self::CELL_TREE_OAK,
    ];

    protected $notPassable = [
        self::CELL_STONE,
        self::CELL_STONE_2,
        self::CELL_CAVE,
        self::CELL_CAVE_2,
        self::CELL_CAVE_3,
        self::CELL_TREE_PINE,
        self::CELL_TREE_PINE_2,
        self::CELL_TREE_PINE_3,
        self::CELL_TREE_OAK,
        self::CELL_FOREST,
        self::CELL_FOREST_2,
        self::CELL_FOREST_3,
        self::CELL_RIVER,
        self::CELL_WALL,
        self::CELL_WALL_2,
    ];

    protected $destroyable = [
        Spell::ENERGY_SOURCE_FIRE => [
            self::CELL_TREE_PINE   => self::CELL_FIELD_3,
            self::CELL_TREE_PINE_2 => self::CELL_FIELD_3,
            self::CELL_TREE_PINE_3 => self::CELL_FIELD_3,
            self::CELL_TREE_OAK    => self::CELL_FIELD_3,
            self::CELL_FOREST      => self::CELL_FIELD_3,
            self::CELL_FOREST_2    => self::CELL_FIELD_3,
            self::CELL_FOREST_3    => self::CELL_FIELD_3,
        ]
    ];
    
    protected $water = [
        self::CELL_RIVER,
    ];

    protected static $generatorConfig = [
        'spawnLocation' => [
            'radius' => 1,
        ],
        'mapLimit' => 30,
        'portalLocation' => ['x' => 0, 'y' => 1],
        'dialog' => [
            'help' => [
                ['method' => 'whereIsWitch']
            ], 'lore' => [
                ['message' => 'I`m not sure who build those portals. They are connecting different parts of the world as well as different worlds.'],
                ['message' => 'My school of magic was not the only one. I heard that there was at least 5 other schools of magic.'],

            ], 'joke' => [
                ['message' => 'Am I the only one here who thinks that those spider are way too big?'],
                ['message' => 'I should have remembered ingredients for that awesome spell...'],

            ], 'turn' => [
                1 => ['message' => 'I have no idea what happened to my school. I hope this witch have some answers.'],
                3 => ['message' => 'I heard that she lives in some kind of house.'],
                5 => ['message' => 'She was teaching dark art in my school back in the days.'],
                7 => ['message' => 'Let`s hope she still have her senses.'],
                9 => ['method' => 'whereIsWitch'],
                11 => ['message' => 'Maybe I need to create more spells?.'],
                15 => ['message' => 'I hoped this forest is more like a park.'],
                //100 => ['message' => 'I h.'],
            ]
        ]
    ];

    public function addAdditionalToMap(array &$map)
    {
        $possibleLocationsForLake = [
            [0, 20], [20, 0], [0, -20], [-20, 0]
        ];
        $lakeLocation = ChanceHelper::oneFromArray($possibleLocationsForLake);
        $newMap = $this->addLocation($lakeLocation[0], $lakeLocation[1], LocationsForest::$lakeWithIsland, $map, 3);

        $possibleLocationsForHut = [
            [20, 20, 0], [-20, -20, 2], [20, -20, 3], [-20, 20, 1]
        ];
        $hutLocation = ChanceHelper::oneFromArray($possibleLocationsForHut);
        $newMap = $this->addLocation(
            $hutLocation[0], $hutLocation[1], LocationsForest::$witchHut, $newMap, $hutLocation[2]
        );

        $witch = null;
        $units = $this->world->getUnits();
        foreach ($units as $y => $row) {
            foreach ($row as $x => $unit) {
                if ($unit['type'] == 3) {
                    // this is witch
                    $witch = $this->world->getUnit($x, $y);
                }
            }
        }
        $this->world->addData('witchLocation', [$witch->getX(), $witch->getY()]);
        $door = null;
        $objects = $this->world->getObjects();
        foreach ($objects as $y => $row) {
            foreach ($row as $x => $object) {
                if ($object['type'] == 50) {
                    // this is witch
                    $door = [$x, $y];
                }
            }
        }
        Event::create(Event::EVENT_UNIT_BEFORE_DYING, [
                'times' => 1,
                'owner' => $witch,
                'doorX' => $door[0], 'doorY' => $door[1]
            ],
            'Objects:openDoor'
        );
        Event::create(Event::EVENT_UNIT_BEFORE_DYING, [
                'times' => 1,
                'owner' => $witch,
                'flag' => 'SecretCave',
                'value' => 'open'
            ],
            'General:addUserFlag'
        );
        Event::create(Event::EVENT_UNIT_BEFORE_DYING, [
                'times' => 1,
                'owner' => $witch,
                'text' => 'Wow. She is gone! Looks like it is a big start for an adventure!', 'stage' => Game::ANIMATION_STAGE_TURN_END_EFFECTS_2
            ],
            'General:mageSay'
        );
        Event::create(Event::EVENT_UNIT_BEFORE_DYING, [
                'times' => 1,
                'owner' => $witch,
                'text' => 'AAAAA! Nooo!', 'stage' => Game::ANIMATION_STAGE_TURN_END_EFFECTS
            ],
            'General:say'
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

                $cell = $this->random[array_rand($this->random)];
                break;
            default:
                throw new \Exception('In ' . __CLASS__ . ' cell for type "' . $type . '" is not defined');
        }
        return $cell;
    }



    public function whereIsWitch()
    {
        $location = $this->world->getData()['witchLocation'];
        $directionText = $this->coordinatsToDirection($this->mage->getX(), $this->mage->getY(), $location[0], $location[1]);

        return 'Witch should be somewhere ' . $directionText . ' from here.';
    }

}