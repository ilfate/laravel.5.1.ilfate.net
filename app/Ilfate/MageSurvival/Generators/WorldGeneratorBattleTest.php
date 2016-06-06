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
class WorldGeneratorBattleTest extends WorldGenerator
{
    const CELL_BURNT_LANDING = 'bl';
    const CELL_FIELD_1 = 'f1';
    const CELL_FIELD_2 = 'f2';
    const CELL_FIELD_3 = 'f3';
    const CELL_FLOOR = 'f4';
    const CELL_WALL = 'w1';
    const CELL_WALL_2 = 'w2';
    const CELL_STONE = 's';
    const CELL_TREE_PINE = 't1';
    const CELL_TREE_PINE_2 = 't2';
    const CELL_TREE_PINE_3 = 't3';
    const CELL_TREE_OAK = 't4';
    const CELL_FOREST = 't0';
    const CELL_FOREST_2 = 'tf';
    const CELL_FOREST_3 = 'tF';
    const CELL_RIVER = 'r1';

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
        self::CELL_FIELD_1,
        self::CELL_FIELD_1,
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

    protected static $generatorConfig = [
        'spawnLocation' => [
            'radius' => 1,
        ],
        'mapLimit' => 40,
        'portalLocation' => ['x' => 0, 'y' => 1]
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

    public function getUnitCreatingChance($x, $y)
    {
        return 8;
    }

    public function getObjectCreatingChance($x, $y)
    {
        return 4;
    }


}