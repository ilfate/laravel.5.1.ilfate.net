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
class WorldGeneratorTutorial extends WorldGenerator
{
    const CELL_BURNT_LANDING = 'bl';
    const CELL_FIELD_1 = 'f1';
    const CELL_FIELD_2 = 'f2';
    const CELL_FIELD_3 = 'f3';
    const CELL_STONE = 's';

    protected $cells = [
        self::CELL_BURNT_LANDING, // birnedLanding
    ];

    protected $random = [
        self::CELL_FIELD_1,
        self::CELL_FIELD_1,
        self::CELL_FIELD_1,
        self::CELL_FIELD_2,
        self::CELL_FIELD_2,
        self::CELL_FIELD_2,
        self::CELL_FIELD_3,
        self::CELL_STONE,
    ];

    protected $notPassable = [
        self::CELL_STONE,
    ];

    /**
     * @param $type
     *
     * @return string
     * @throws \Exception
     */
    public function getCellByType($type)
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