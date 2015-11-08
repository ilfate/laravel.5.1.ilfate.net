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
trait Module
{

    /**
     * @param bool|true $isEncoded
     *
     * @return array|string
     */
    public function exportAsJson($isEncoded = true)
    {
        $resultJsonArray = [
            'cells' => [],
            'x' => $this->getX(),
            'y' => $this->getY(),
            'id' => $this->getId(),
        ];
        foreach ($this->cells as $cell) {
            $resultJsonArray['cells'][] = $cell->exportAsJson(false);
        }
        if ($isEncoded) {
            $resultJsonArray = json_encode($resultJsonArray);
        }
        return $resultJsonArray;
    }
}