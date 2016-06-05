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
namespace Ilfate\MageSurvival;

/**
 * TODO: Short description.
 * TODO: Long description here.
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
class ChanceHelper
{
    public static function chance($percent)
    {
        $rand = mt_rand(0,99);
        if ($rand >= $percent) return false;
        return true;
    }

    public static function oneFromArray($array)
    {
        return $array[array_rand($array)];
    }

    public static function extractOne(&$array)
    {
        $key = array_rand($array);
        $value = $array[$key];
        unset($array[$key]);
        return $value;
    }
}