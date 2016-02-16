<?php
/**
 * TODO: Package description.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @copyright 2016 Watchmaster GmbH
 * @license   Proprietary license.
 * @link      http://www.watchmaster.de
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
                    $map[$y][$x] = $this->getCellByType(self::CELL_TYPE_RANDOM);
                }
            }
        }
        $this->world->setMap($map);
        $this->world->save();
        $this->mage->setX(0);
        $this->mage->setY(0);
        $this->mage->save();
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
            }
        }
        $this->world->saveIfChanged();
        return $map;
    }

    public function getOrGenerateCell($x, $y)
    {
        $map = $this->world->getMap();
        if (empty($map[$y][$x])) {
            $map[$y][$x] = $this->getCellByType(self::CELL_TYPE_RANDOM);
            $this->world->setMap($map);
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