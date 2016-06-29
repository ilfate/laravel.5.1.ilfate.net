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
use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\GameBuilder;
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
class WorldGeneratorSchool extends WorldGenerator
{
    const CELL_BURNT_LANDING = 'bl';
    const CELL_FIELD_1 = 'f1';
    const CELL_FIELD_2 = 'f2';
    const CELL_FIELD_3 = 'f3';
    const CELL_FIELD_4 = 'f4';
    const CELL_STONE = 's';
    const CELL_STELLAR_1 = 's1';
    const CELL_WALL   = 'w1';
    const CELL_WALL_2 = 'w2';
    const CELL_WALL_3 = 'w3';
    const CELL_WALL_4 = 'w4';
    const CELL_WALL_5 = 'w5';
    const CELL_WALL_6 = 'w6';

    protected $cells = [
        self::CELL_BURNT_LANDING, // birnedLanding
    ];

    protected $random = [
        self::CELL_WALL,
        self::CELL_WALL,
        self::CELL_WALL,
        self::CELL_WALL,
        self::CELL_WALL_2,
        self::CELL_WALL_2,
        self::CELL_WALL_2,
        self::CELL_WALL_2,
        self::CELL_WALL_3,
        self::CELL_WALL_3,
        self::CELL_WALL_3,
        self::CELL_WALL_3,
        self::CELL_WALL_4,
        self::CELL_WALL_5,
        self::CELL_WALL_6,
    ];

    protected $notPassable = [
        self::CELL_STONE,
        self::CELL_STELLAR_1,
        self::CELL_WALL,
        self::CELL_WALL_2,
        self::CELL_WALL_3,
        self::CELL_WALL_4,
        self::CELL_WALL_5,
        self::CELL_WALL_6,
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