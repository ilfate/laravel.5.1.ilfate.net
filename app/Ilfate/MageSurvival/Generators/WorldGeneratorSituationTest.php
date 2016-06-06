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
class WorldGeneratorSituationTest extends WorldGenerator
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
        'world-predefined' => true,
        'full-world' => array ( 0 => array ( 1 => 'f1', 2 => 'f1', 3 => 'f1-101', 4 => 'f1', 5 => 'f1', 6 => 'f1', 0 => 'f1', -1 => 'f1', 7 => 'f1', 8 => 'f1', ), 1 => array ( 4 => 's', 5 => 'f1', 6 => 'f1', 0 => 'f1', 1 => 'f1', -1 => 'f1', 2 => 'f1', 3 => 'f1', 7 => 'f1', 8 => 'f1', ), -1 => array ( 4 => 'f1', 5 => 'f1', 6 => 'f1', 0 => 'f1', 1 => 'f1', -1 => 'f1+1000', 2 => 'f1', 3 => 'f1', 7 => 'f1', 8 => 'f1', ), 2 => array ( 0 => 'f1', 1 => 'f1', 2 => 'f1', 3 => 'f1', 4 => 'f1', 5 => 'f1', 6 => 'f1', -1 => 'f1', ), -2 => array ( 0 => 'f1', 1 => 'f1', 2 => 'f1', 3 => 'f1', 4 => 'f1', 5 => 'f1', 6 => 'f1', -1 => 'f1', 7 => 'f1', 8 => 'f1', ), -3 => array ( 0 => 'f1', 1 => 'f1', 2 => 'f1', 3 => 'f1', 4 => 'f1', 5 => 'f1', 6 => 'f1', 7 => 'f1', 8 => 'f1', ), )
    ];



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

}