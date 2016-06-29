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
use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
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
class WorldGeneratorHome extends WorldGeneratorSchool
{
    protected static $generatorConfig = [
        'world-predefined' => true,
        'full-world' =>
            array ( 0 => array ( 0 => 'f1+1000', 1 => 'f2', 2 => 's1', 3 => 'f4', 4 => 'f1', 5 => 'f2', 9 => 'f3', 10 => 'f1', 11 => 'f2', 12 => 'f1', 13 => 'f4', 14 => 'f2', 18 => 'f4', -18 => 'f4', -14 => 'f2', -13 => 'f4', -12 => 'f1', -11 => 'f2', -10 => 'f1', -9 => 'f3', -5 => 'f2', -4 => 'f1', -3 => 'f4', -2 => 's1', -1 => 'f2', ), 1 => array ( 0 => 'f4', 1 => 'f3', 2 => 'f4', 3 => 'f1', 4 => 'f2', 5 => 'f3', 7 => 'f4', 8 => 'f3', 9 => 'f2', 12 => 'f3', 13 => 'f1', 14 => 'f2', 15 => 'f3', 17 => 'f3', 18 => 'f2', -18 => 'f2', -17 => 'f3', -15 => 'f3', -14 => 'f2', -13 => 'f1', -12 => 'f3', -9 => 'f1', -8 => 'f2', -7 => 'f2', -5 => 'f1', -4 => 'f4', -3 => 'f3', -2 => 'f2', -1 => 'f1', ), 2 => array ( 0 => 's1', 1 => 'f2', 2 => 'f1', 3 => 'f2', 4 => 's1', 5 => 'f4', 6 => 'f1', 7 => 'f3', 8 => 'f1', 9 => 'f4', 12 => 'f4', 13 => 'f3', 14 => 'f2', 15 => 'f2', 16 => 'f1', 17 => 'f2', 18 => 'f4', -18 => 'f4', -17 => 'f2', -16 => 'f1', -15 => 'f2', -14 => 'f2', -13 => 'f3', -12 => 'f4', -9 => 'f4', -8 => 'f1', -7 => 'f3', -6 => 'f1', -5 => 'f4', -4 => 's1', -3 => 'f2', -2 => 'f1', -1 => 'f4', ), 3 => array ( 0 => 'f2', 1 => 'f1', 2 => 'f4', 3 => 'f3', 4 => 'f4', 5 => 'f1', 7 => 'f2', 8 => 'f2', 9 => 'f1', -9 => 'f2', -8 => 'f3', -7 => 'f4', -5 => 'f3', -4 => 'f2', -3 => 'f1', -2 => 'f4', -1 => 'f3', ), 4 => array ( 0 => 'f1', 1 => 'f4', 2 => 's1', 3 => 'f2', 4 => 'f1', 5 => 'f1', 8 => 'f1', -8 => 'f1', -5 => 'f1', -4 => 'f1', -3 => 'f4', -2 => 's1', -1 => 'f2', ), 5 => array ( 0 => 'f4', 1 => 'f3', 2 => 'f2', 3 => 'f1', 4 => 'f1', 5 => 'f3', 8 => 'f1', -8 => 'f1', -5 => 'f1', -4 => 'f1', -3 => 'f3', -2 => 'f2', -1 => 'f1', ), 6 => array ( 2 => 'f1', 8 => 'f2', -8 => 'f2', -2 => 'f1', ), 7 => array ( 1 => 'f2', 2 => 'f3', 3 => 'f4', 7 => 'f1+1000', 8 => 'f3', -8 => 'f3', -7 => 'f1+1000', -3 => 'f2', -2 => 'f3', -1 => 'f4', ), 8 => array ( 1 => 'f2', 2 => 'f1', 3 => 'f3', 4 => 'f1', 5 => 'f1', 6 => 'f2', 7 => 'f3', 8 => 'f1', -8 => 'f1', -7 => 'f3', -6 => 'f2', -5 => 'f1', -4 => 'f1', -3 => 'f2', -2 => 'f1', -1 => 'f3', ), 9 => array ( 0 => 'f3', 1 => 'f1', 2 => 'f4', 3 => 'f2', -3 => 'f1', -2 => 'f4', -1 => 'f2', ), -11 => array ( 0 => 'f2', ), -10 => array ( 0 => 'f1', ), -9 => array ( 0 => 'f3', 1 => 'f2', 2 => 'f4', 3 => 'f1', -3 => 'f2', -2 => 'f4', -1 => 'f1', ), -8 => array ( 1 => 'f3', 2 => 'f1', 3 => 'f2', 4 => 'f1', 5 => 'f1', 6 => 'f2', 7 => 'f3', 8 => 'f1', -8 => 'f1', -7 => 'f3', -6 => 'f2', -5 => 'f1', -4 => 'f1', -3 => 'f3', -2 => 'f1', -1 => 'f2', ), -7 => array ( 1 => 'f4', 2 => 'f3', 3 => 'f2', 7 => 'f1+1000', 8 => 'f3', -8 => 'f3', -7 => 'f1+1000', -3 => 'f4', -2 => 'f3', -1 => 'f2', ), -6 => array ( 2 => 'f1', 8 => 'f2', -8 => 'f2', -2 => 'f1', ), -5 => array ( 0 => 'f4', 1 => 'f1', 2 => 'f2', 3 => 'f3', 4 => 'f1', 5 => 'f1', 8 => 'f1', -8 => 'f1', -5 => 'f3', -4 => 'f1', -3 => 'f1', -2 => 'f2', -1 => 'f3', ), -4 => array ( 0 => 'f1', 1 => 'f2', 2 => 's1', 3 => 'f4', 4 => 'f1', 5 => 'f1', 8 => 'f1', -8 => 'f1', -5 => 'f1', -4 => 'f1', -3 => 'f2', -2 => 's1', -1 => 'f4', ), -3 => array ( 0 => 'f2', 1 => 'f3', 2 => 'f4', 3 => 'f1', 4 => 'f2', 5 => 'f3', 7 => 'f4', 8 => 'f3', 9 => 'f2', -9 => 'f1', -8 => 'f2', -7 => 'f2', -5 => 'f1', -4 => 'f4', -3 => 'f3', -2 => 'f4', -1 => 'f1', ), -2 => array ( 0 => 's1', 1 => 'f4', 2 => 'f1', 3 => 'f2', 4 => 's1', 5 => 'f4', 6 => 'f1', 7 => 'f3', 8 => 'f1', 9 => 'f4', 12 => 'f4', 13 => 'f3', 14 => 'f2', 15 => 'f2', 16 => 'f1', 17 => 'f2', 18 => 'f4', -18 => 'f4', -17 => 'f2', -16 => 'f1', -15 => 'f2', -14 => 'f2', -13 => 'f3', -12 => 'f4', -9 => 'f4', -8 => 'f1', -7 => 'f3', -6 => 'f1', -5 => 'f4', -4 => 's1', -3 => 'f2', -2 => 'f1', -1 => 'f2', ), -1 => array ( 0 => 'f4', 1 => 'f1', 2 => 'f2', 3 => 'f3', 4 => 'f4', 5 => 'f1', 7 => 'f2', 8 => 'f2', 9 => 'f1', 12 => 'f2', 13 => 'f1', 14 => 'f2', 15 => 'f3', 17 => 'f3', 18 => 'f2', -18 => 'f2', -17 => 'f3', -15 => 'f3', -14 => 'f2', -13 => 'f1', -12 => 'f2', -9 => 'f2', -8 => 'f3', -7 => 'f4', -5 => 'f3', -4 => 'f2', -3 => 'f1', -2 => 'f4', -1 => 'f3', ), )
            ,
        //'portalLocation' => ['x' => 0, 'y' => 1],
//        'dialog' => [
//            'help' => [
//                ['method' => 'tutorialMessage']
//            ], 'turn' => [
//                1 => ['message' => 'OMG what happened to my school?? Oh I found fireball spell. Who dropped it here?'],
//                3 => ['message' => 'Where is everybody? All the teachers and students are missing!'],
//                5 => ['message' => 'Ok here is the wand of one of the teachers. She is a witch.'],
//                7 => ['message' => 'Maybe I can find her? I remember that she was living in a forest.'],
//                11 => ['message' => 'I just need to get to the portal.'],
                //100 => ['message' => 'I h.'],
//            ]
//        ]
    ];

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        
    }
    
}