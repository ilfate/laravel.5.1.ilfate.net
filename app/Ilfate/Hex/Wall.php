<?php
/**
 * TODO: Package description.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @license   Proprietary license.
 * @version   "SVN: $Id$"
 * @link      http://ilfate.net
 */
namespace Ilfate\Hex;

/**
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
class Wall extends Cell
{
    const COLOR_0 = '0';
    const COLOR_1 = '1';
    const COLOR_2 = '2';

    public static $directionColors = [
        0 => self::COLOR_0,
        1 => self::COLOR_1,
        2 => self::COLOR_2,
        3 => self::COLOR_0,
        4 => self::COLOR_1,
        5 => self::COLOR_2,
    ];

    protected $turnsLeft = 10;

    protected $colors = [];

    /**
     * @return mixed
     */
    public function export()
    {
        return [
            'type' => self::TYPE_WALL,
            'x' => $this->x,
            'y' => $this->y,
            'turnsLeft' => $this->turnsLeft,
            'colors' => $this->colors,
        ];
    }

    /**
     * @param array $data
     */
    public function import($data)
    {
        $this->turnsLeft = $data['turnsLeft'];
        $this->colors = $data['colors'];
    }

    /**
     * @return string
     */
    public function getAdditionalClasses()
    {
        $colors = $this->getColors();
        sort($colors);
        return 'wall-' . implode('-', $colors);
    }

    public function addColors($colors)
    {
        $this->colors = array_unique(array_merge($this->getColors(), $colors));
    }

    /**
     * @param $laserType
     *
     * @return bool
     */
    public function isPassableForLaser($laserType)
    {
        if (in_array($laserType, $this->getColors())) {
            return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * @param array $colors
     */
    public function setColors($colors)
    {
        $this->colors = $colors;
    }

}