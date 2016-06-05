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

    public static $witchHut = array ( 0 => array ( 0 => 'f4', 1 => 'w2', 2 => 'f4', 3 => 'f4', 4 => 'f4', 5 => 'f4', -5 => 'f4', -4 => 'f4', -3 => 'f4', -2 => 'f4', -1 => 'w2', -6 => 'w1', 6 => 'w1', 7 => 'w1', 8 => 'f4', 9 => 'w1', -9 => 'w1', -8 => 'f4', -7 => 'w1', ), 1 => array ( 0 => 'f4-3', 1 => 'f4', 2 => 'f4', 3 => 'f4', 4 => 'f4', -4 => 'f4', -3 => 'f4', -2 => 'f4', -1 => 'f4', -5 => 'w1', 5 => 'w1', 6 => 'w1', 7 => 'w1', 8 => 'f4', 9 => 'w1', 10 => 'w1', -10 => 'w1', -9 => 'w1', -8 => 'f4', -7 => 'w1', -6 => 'w1', ), 2 => array ( 0 => 'f4', 1 => 'f4', -1 => 'f4', 2 => 'w1', -5 => 'w1', -4 => 'w1', -3 => 'w1', -2 => 'w1', 3 => 'w1', 4 => 'w1', 5 => 'w1', 6 => 'w1', 7 => 'f4', 8 => 'f4', 9 => 'f4', 10 => 'w1', -10 => 'w1', -9 => 'f4', -8 => 'f4', -7 => 'f4', -6 => 'w1', ), 3 => array ( 0 => 'f4+50', 1 => 'w1', -1 => 'w1', 2 => 'w1', 6 => 'w1', 7 => 'f4', 8 => 'f4', 9 => 'f4', 10 => 'w1', -10 => 'w1', -9 => 'f4', -8 => 'f4', -7 => 'f4', -6 => 'w1', -2 => 'w1', ), 4 => array ( 0 => 'f4', 1 => 'f4', -1 => 'f4', 2 => 'w1', 6 => 'w1', 7 => 'w1', 8 => 'w1', 9 => 'w1', 10 => 'w1', -10 => 'w1', -9 => 'w1', -8 => 'w1', -7 => 'w1', -6 => 'w1', -2 => 'w1', ), 5 => array ( 0 => 'f4', 1 => 'f4+3', -1 => 'f4+3', 2 => 'w1', -2 => 'w1', ), 6 => array ( 0 => 'f4+1000', 1 => 'f4', -1 => 'f4', 2 => 'w1', -2 => 'w1', ), -5 => array ( 0 => 'f4', 1 => 'f4', -1 => 'f4', 2 => 'w1', -2 => 'w1', 3 => 'w1', -3 => 'w1', ), -4 => array ( 0 => 'f4', 1 => 'f4', 2 => 'f4', -2 => 'f4', -1 => 'f4', 3 => 'w1', 4 => 'w1', -4 => 'w1', -3 => 'w1', 5 => 'w1', -5 => 'w1', ), -3 => array ( 0 => 'w2', 1 => 'f4', 2 => 'f4', 3 => 'f4', 4 => 'f4', -4 => 'f4', -3 => 'f4', -2 => 'f4', -1 => 'f4', 5 => 'w1', -5 => 'w1', 6 => 'w1', -6 => 'w1', ), -2 => array ( 0 => 'f4', 1 => 'f4', 2 => 'w2', 3 => 'f4', 4 => 'f4', 5 => 'f4', -4 => 'f4', -3 => 'f4', -2 => 'w2', -1 => 'f4', -5 => 'f4', 6 => 'w1', 7 => 'w1', 8 => 'w1', 9 => 'w1', -9 => 'w1', -8 => 'w1', -7 => 'w1', -6 => 'w1', ), -1 => array ( 0 => 'f4', 1 => 'f4', 2 => 'f4', 3 => 'f4', 4 => 'w2', 5 => 'f4', -5 => 'f4', -4 => 'w2', -3 => 'f4', -2 => 'f4', -1 => 'f4', -6 => 'f4', 6 => 'f4', 7 => 'f4', 8 => 'f4', 9 => 'w1', -9 => 'w1', -8 => 'f4', -7 => 'f4', ), -6 => array ( 1 => 'w1', -1 => 'w1', 0 => 'f4', 2 => 'w1', -2 => 'w1', ), 7 => array ( 0 => 'w1', 1 => 'w1', 2 => 'w1', -2 => 'w1', -1 => 'w1', ), -10 => array ( 0 => 'f1', 1 => 'f1', -1 => 'f1', ), -9 => array ( 0 => 'f4', 1 => 'f1', 2 => 'f1', 3 => 'f1', -3 => 'f1', -2 => 'f1', -1 => 'f1', ), -8 => array ( 0 => 'f4', 1 => 'f1', -1 => 'f1', ), -7 => array ( 0 => 'f4', 1 => 'f1', -1 => 'f1', ), );

    public static $spiderNest = array ( 0 => array ( 0 => 'r2-101', 1 => 'r2', 2 => 'r2', -2 => 'r2', -1 => 'r2', -4 => 'cC', -3 => 'c1', -23 => 'c1', -8 => 's1', -6 => 's1', ), 1 => array ( 0 => 'r2', 1 => 'r2', 2 => 's1', -2 => 's1', -1 => 'r2', -4 => 's1', -3 => 'c2', -23 => 'c1', ), 2 => array ( 0 => 'r2', 1 => 'r2', 2 => 's1', -2 => 's1', -1 => 'r2', -4 => 'cc', -3 => 'c1', -23 => 'c1', ), 3 => array ( 0 => 'r2', 1 => 'r2', 2 => 's1', -2 => 's1', -1 => 'r2', -4 => 's1', -3 => 'c1', -23 => 'c1', ), 4 => array ( 0 => 'r2', 1 => 'r2', 2 => 's1', -2 => 's1', -1 => 'r2', -4 => 'c', -3 => 'c2', -23 => 'c1', -19 => 'c1', -18 => 'c1+3', -17 => 'c1', ), 5 => array ( 0 => 'r2', 1 => 'r2', 2 => 's1', -2 => 's1', -1 => 'r2', -3 => 'c1', -23 => 'c1', -19 => 'c1', -18 => 'c1', -17 => 'c1', ), 6 => array ( 0 => 'r2', 1 => 'r2', -1 => 'r2', -23 => 'c1', -22 => 'c2', -21 => 'c2', -20 => 'c2', -19 => 'c1', -18 => 'c1', -17 => 'c1+3', ), 7 => array ( 0 => 'r2', 1 => 'r2', -1 => 'r2', -19 => 'c1', -18 => 'c1', -17 => 'c1', ), -2 => array ( 0 => 'r2', 1 => 's1', 2 => 's1', -2 => 's1', -1 => 's1', -4 => 'cc', -3 => 'c2', -7 => 's1', ), -1 => array ( 0 => 'r2', 1 => 'r2', -1 => 'r2', 2 => 's1', -2 => 's1', -4 => 's1', -3 => 'c1', 4 => 's1', 6 => 's1', -23 => 'c1', ), -4 => array ( 0 => 's1', 1 => 's1', -1 => 's1', -6 => 's1', -5 => 'c', -4 => 's1', -3 => 'cC', -2 => 'c1', ), -3 => array ( 0 => 's1', 1 => 's1', -1 => 's1', -5 => 's1', -4 => 's1', -3 => 'c1', -2 => 'c2', 4 => 's1', ), -10 => array ( -6 => 's1', -4 => 'cC', 0 => 's1', 3 => 's1', 4 => 's1', ), -9 => array ( -6 => 's1', -5 => 'c', -4 => 'cc', -3 => 'c', 6 => 's1', -17 => 's1', -15 => 's1', ), -8 => array ( -18 => 'c1+3', -17 => 'c1', -16 => 'c1', -15 => 'c1', -14 => 'c1', -9 => 'c', -8 => 'c', -7 => 'cC', -6 => 'cc', -5 => 's1', -4 => 'cc', -3 => 'c', -2 => 'c', 2 => 's1', ), -7 => array ( 0 => 'c2', -18 => 'c1+3', -17 => 'c1', -16 => 'c1', -15 => 'c1', -14 => 'c1', -13 => 'c1', -12 => 'c1', -11 => 'c1', -10 => 'c1', -9 => 'c1', -8 => 'c1', -7 => 's1', -6 => 'cC', -5 => 'c', -4 => 'c', -3 => 'c1', -2 => 'c2', -1 => 'c2', 5 => 's1', 6 => 's1', 7 => 's1', -19 => 's1', ), -6 => array ( 0 => 'c1', -18 => 'c1+3', -17 => 'c1', -16 => 'c1', -15 => 'c1', -14 => 'c1', -9 => 'c', -8 => 'c', -7 => 'c', -6 => 'c', -5 => 'cc', -4 => 'cC', -3 => 's1', -2 => 's1', -1 => 'cc', 3 => 's1', 4 => 's1', ), -5 => array ( -6 => 's1', -5 => 's1', -4 => 'c', -3 => 'c', 0 => 'c2', 2 => 's1', 4 => 's1', -17 => 's1', -15 => 's1', -2 => 'c1', -1 => 'c1', ), 8 => array ( -19 => 'c1', -18 => 'c1', -17 => 'c1', ), -11 => array ( -2 => 's1', ), 9 => array ( -18 => 'c1', ), 10 => array ( -18 => 'c1', ), 11 => array ( -18 => 'c1', ), 12 => array ( -18 => 'c1', ), 13 => array ( -18 => 'c1', ), 14 => array ( -21 => 'c2', -20 => 'c2', -19 => 'c2', -18 => 'c1', ), 15 => array ( -21 => 'c1', ), 16 => array ( -21 => 'c1', ), 17 => array ( -21 => 'c1', -35 => 'c2', -34 => 'c2', -33 => 'c2', -32 => 'c2', -31 => 'c2', -30 => 'c2', -29 => 'c2', -28 => 'c2', -27 => 'c2', -26 => 'c2', -25 => 'c2', ), 18 => array ( -24 => 'c2', -23 => 'c2', -22 => 'c2', -21 => 'c1', -35 => 'c2-3', -34 => 'c2', -33 => 'c2', -32 => 'c2', -31 => 'c2', -30 => 'c2', -29 => 'c2', -28 => 'c2', -27 => 'c2', -26 => 'c2', -25 => 'c2', ), 19 => array ( -35 => 'c2', -34 => 'c2', -33 => 'c2', -32 => 'c2', -31 => 'c2', -30 => 'c2', -29 => 'c2', -28 => 'c2', -27 => 'c2', -26 => 'c2', -25 => 'c2', ), );

}