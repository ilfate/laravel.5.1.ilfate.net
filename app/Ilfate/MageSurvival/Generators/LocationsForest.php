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
class LocationsForest
{

    public static $meadow = array();

    public static $lakeWithIsland = array ( 0 => array ( 0 => 'f1', 1 => 'f1', 2 => 'r1', 3 => 'r1', 4 => 'r1', 5 => 'r1', 6 => 'r1', -6 => 'r1', -5 => 'r1', -4 => 'r1', -3 => 'r1', -2 => 'r1', -1 => 'f1', ), -5 => array ( 0 => 'r1', 1 => 'r1', 2 => 'r1', 3 => 'r1', -5 => 'r1', -4 => 'r1', -3 => 'r1', -2 => 'r1', -1 => 'r1', ), -4 => array ( 0 => 'r1', 1 => 'r1', 2 => 'r1', 3 => 'r1', 4 => 'r1', -5 => 'r1', -4 => 'r1', -3 => 'r1', -2 => 'r1', -1 => 'r1', ), -3 => array ( 0 => 'r1', 1 => 'r1', 2 => 'r1', 3 => 'r1', 4 => 'r1', 5 => 'r1', -6 => 'r1', -5 => 'r1', -4 => 'r1', -3 => 'r1', -2 => 'r1', -1 => 'r1', ), -2 => array ( 0 => 'r1', 1 => 'r1', 2 => 'r1', 3 => 'r1', 4 => 'r1', 5 => 'r1', -6 => 'r1', -5 => 'r1', -4 => 'r1', -3 => 'r1', -2 => 'r1', -1 => 'r1', ), -1 => array ( 0 => 'f1', 1 => 'f1', 2 => 'r1', 3 => 'r1', 4 => 'r1', 5 => 'r1', 6 => 'r1', -6 => 'r1', -5 => 'r1', -4 => 'r1', -3 => 'r1', -2 => 'r1', -1 => 'f1', ), 1 => array ( 0 => 'f1', 1 => 'f1', 2 => 'r1', 3 => 'r1', 4 => 'r1', 5 => 'r1', 6 => 'r1', -6 => 'r1', -5 => 'r1', -4 => 'r1', -3 => 'r1', -2 => 'r1', -1 => 'f1', ), 2 => array ( 0 => 'r1', 1 => 'r1', 2 => 'r1', 3 => 'r1', 4 => 'r1', 5 => 'r1', -5 => 'r1', -4 => 'r1', -3 => 'r1', -2 => 'r1', -1 => 'r1', ), 3 => array ( 0 => 'r1', 1 => 'r1', 2 => 'r1', 3 => 'r1', 4 => 'r1', 5 => 'r1', -5 => 'r1', -4 => 'r1', -3 => 'r1', -2 => 'r1', -1 => 'r1', ), 4 => array ( 0 => 'r1', 1 => 'r1', 2 => 'r1', 3 => 'r1', 4 => 'r1', -4 => 'r1', -3 => 'r1', -2 => 'r1', -1 => 'r1', ), 5 => array ( 0 => 'r1', 1 => 'r1', 2 => 'r1', 3 => 'r1', -4 => 'r1', -3 => 'r1', -2 => 'r1', -1 => 'r1', ), -6 => array ( 0 => 'r1', 1 => 'r1', -3 => 'r1', -2 => 'r1', -1 => 'r1', ), );
}