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
namespace Ilfate\Cosmos\Ship;

/**
 * TODO: Short description.
 * TODO: Long description here.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>

 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
trait Exportable
{
    /**
     * @return array
     */
    static function getExportList()
    {
        return [];
    }
    public function serialise()
    {
        $arr = [];
        foreach (self::getExportList() as $fieldName) {
            $arr[] = $this->{$fieldName};
        }

        return implode(';', $arr);
    }

    /**
     * @param $string
     *
     * @return $this
     */
    public static function createFromSerialised($string)
    {
        $newObj = new self();
        $dataArr = explode(';', $string);
        $i = 0;
        $exportList = self::getExportList();
        foreach ($dataArr as $value) {
            $newObj->{$exportList[$i]} = $value;
            $i++;
        }
        return $newObj;
    }
}