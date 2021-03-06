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
trait Ship
{

    /**
     * @return string
     */
    public function exportAsJson()
    {
        $resultJsonArray = [
            'modules' => []
        ];
        foreach ($this->modules as $module) {
            $resultJsonArray['modules'][] = $module->exportAsJson(false);
        }
        return json_encode($resultJsonArray);
    }
}