<?php
/**
 * TODO: Package description.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilya.rubinchik@home24.de>
 * @copyright 2012-2013 Home24 GmbH
 * @license   Proprietary license.
 * @version   "SVN: $Id$"
 * @link      http://www.fp-commerce.de
 */
namespace Ilfate\Cosmos\Ship\FrontEnd;

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
trait Cell
{

    /**
     * @param bool|true $isEncoded
     *
     * @return array|string
     */
    public function exportAsJson($isEncoded = true)
    {
        $resultJsonArray = [];
        $stringArr = [];
        $doors = $this->getDoors();
        foreach ($doors as $doorDiraction) {
            $stringArr[] = self::CELL_DOOR_CLASS . $doorDiraction;
        }
        $resultJsonArray['doors'] = implode(' ', $stringArr);
        $resultJsonArray['x'] = $this->getX();
        $resultJsonArray['y'] = $this->getY();
        $resultJsonArray['moduleId'] = $this->getModule()->getId();
        if ($isEncoded) {
            $resultJsonArray = json_encode($resultJsonArray);
        }
        return $resultJsonArray;
    }
}