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

    protected $turnsLeft = 10;

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
        ];
    }

    /**
     * @param array $data
     */
    public function import($data)
    {
        $this->turnsLeft = $data['turnsLeft'];
    }

}