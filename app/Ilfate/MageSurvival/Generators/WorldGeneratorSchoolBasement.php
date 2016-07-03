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
class WorldGeneratorSchoolBasement extends WorldGeneratorSchool
{
    protected static $generatorConfig = [
        'world-predefined' => true,
        'full-world' =>
            array ( 0 => array ( 0 => 'f1+1000', 1 => 'f2', 2 => 's1', 3 => 'f4', 4 => 'f1', 5 => 'f2', 9 => 'f3', 10 => 'f1', 11 => 'f2', 12 => 'f1', 13 => 'f1', 14 => 'f2', 18 => 'f1+115', -18 => 'f1+115', -14 => 'f2', -13 => 'f1', -12 => 'f1', -11 => 'f2', -10 => 'f1', -9 => 'f3', -5 => 'f2', -4 => 'f1', -3 => 'f4', -2 => 's1', -1 => 'f2', ), 1 => array ( 0 => 'f4', 1 => 'f3', 2 => 'f4', 3 => 'f1', 4 => 'f2', 5 => 'f3', 7 => 'f4', 8 => 'f3', 9 => 'f2', 12 => 'f3', 13 => 'f1', 14 => 'f2', 15 => 'f3', 17 => 'f3', 18 => 'f2', -18 => 'f2', -17 => 'f3', -15 => 'f3', -14 => 'f2', -13 => 'f1', -12 => 'f3', -9 => 'f1', -8 => 'f2', -7 => 'f2', -5 => 'f1', -4 => 'f4', -3 => 'f3', -2 => 'f2', -1 => 'f1', ), 2 => array ( 0 => 's1', 1 => 'f2', 2 => 'f1', 3 => 'f2', 4 => 's1', 5 => 'f4', 6 => 'f1', 7 => 'f3', 8 => 'f1', 9 => 'f4', 12 => 'f4', 13 => 'f3', 14 => 'f2', 15 => 'f2', 16 => 'f1', 17 => 'f2', 18 => 'f4', -18 => 'f4', -17 => 'f2', -16 => 'f1', -15 => 'f2', -14 => 'f2', -13 => 'f3', -12 => 'f4', -9 => 'f4', -8 => 'f1', -7 => 'f3', -6 => 'f1', -5 => 'f4', -4 => 's1', -3 => 'f2', -2 => 'f1', -1 => 'f4', ), 3 => array ( 0 => 'f2', 1 => 'f1', 2 => 'f4', 3 => 'f3', 4 => 'f4', 5 => 'f1', 7 => 'f2', 8 => 'f2', 9 => 'f1', -9 => 'f2', -8 => 'f3', -7 => 'f4', -5 => 'f3', -4 => 'f2', -3 => 'f1', -2 => 'f4', -1 => 'f3', ), 4 => array ( 0 => 'f1', 1 => 'f4', 2 => 's1', 3 => 'f2', 4 => 'f1', 5 => 'f1', 8 => 'f1', -8 => 'f1', -5 => 'f1', -4 => 'f1', -3 => 'f4', -2 => 's1', -1 => 'f2', ), 5 => array ( 0 => 'f4', 1 => 'f3', 2 => 'f2', 3 => 'f1', 4 => 'f1', 5 => 'f3', 8 => 'f1', -8 => 'f1', -5 => 'f1', -4 => 'f1', -3 => 'f3', -2 => 'f2', -1 => 'f1', ), 6 => array ( 2 => 'f1', 8 => 'f2', -8 => 'f2', -2 => 'f1', ), 7 => array ( 1 => 'f2', 2 => 'f3', 3 => 'f4', 7 => 'f1+1000', 8 => 'f3', -8 => 'f3', -7 => 'f1+1000', -3 => 'f2', -2 => 'f3', -1 => 'f4', ), 8 => array ( 1 => 'f2', 2 => 'f1', 3 => 'f3', 4 => 'f1', 5 => 'f1', 6 => 'f2', 7 => 'f3', 8 => 'f1', -8 => 'f1', -7 => 'f3', -6 => 'f2', -5 => 'f1', -4 => 'f1', -3 => 'f2', -2 => 'f1', -1 => 'f3', ), 9 => array ( 0 => 'f3', 1 => 'f1', 2 => 'f4', 3 => 'f2', -3 => 'f1', -2 => 'f4', -1 => 'f2', ), -11 => array ( 0 => 'f2', ), -10 => array ( 0 => 'f1', ), -9 => array ( 0 => 'f3', 1 => 'f2', 2 => 'f4', 3 => 'f1', -3 => 'f2', -2 => 'f4', -1 => 'f1', ), -8 => array ( 1 => 'f3', 2 => 'f1', 3 => 'f2', 4 => 'f1', 5 => 'f1', 6 => 'f2', 7 => 'f3', 8 => 'f1', -8 => 'f1', -7 => 'f3', -6 => 'f2', -5 => 'f1', -4 => 'f1', -3 => 'f3', -2 => 'f1', -1 => 'f2', ), -7 => array ( 1 => 'f4', 2 => 'f3', 3 => 'f2', 7 => 'f1+1000', 8 => 'f3', -8 => 'f3', -7 => 'f1+1000', -3 => 'f4', -2 => 'f3', -1 => 'f2', ), -6 => array ( 2 => 'f1', 8 => 'f2', -8 => 'f2', -2 => 'f1', ), -5 => array ( 0 => 'f4', 1 => 'f1', 2 => 'f2', 3 => 'f3', 4 => 'f1', 5 => 'f1', 8 => 'f1', -8 => 'f1', -5 => 'f3', -4 => 'f1', -3 => 'f1', -2 => 'f2', -1 => 'f3', ), -4 => array ( 0 => 'f1', 1 => 'f2', 2 => 's1', 3 => 'f4', 4 => 'f1', 5 => 'f1', 8 => 'f1', -8 => 'f1', -5 => 'f1', -4 => 'f1', -3 => 'f2', -2 => 's1', -1 => 'f4', ), -3 => array ( 0 => 'f2', 1 => 'f3', 2 => 'f4', 3 => 'f1', 4 => 'f2', 5 => 'f3', 7 => 'f4', 8 => 'f3', 9 => 'f2', -9 => 'f1', -8 => 'f2', -7 => 'f2', -5 => 'f1', -4 => 'f4', -3 => 'f3', -2 => 'f4', -1 => 'f1', ), -2 => array ( 0 => 's1', 1 => 'f4', 2 => 'f1', 3 => 'f2', 4 => 's1', 5 => 'f4', 6 => 'f1', 7 => 'f3', 8 => 'f1', 9 => 'f4', 12 => 'f4', 13 => 'f3', 14 => 'f2', 15 => 'f2', 16 => 'f1', 17 => 'f2', 18 => 'f4', -18 => 'f4', -17 => 'f2', -16 => 'f1', -15 => 'f2', -14 => 'f2', -13 => 'f3', -12 => 'f4', -9 => 'f4', -8 => 'f1', -7 => 'f3', -6 => 'f1', -5 => 'f4', -4 => 's1', -3 => 'f2', -2 => 'f1', -1 => 'f2', ), -1 => array ( 0 => 'f4', 1 => 'f1', 2 => 'f2', 3 => 'f3', 4 => 'f4', 5 => 'f1', 7 => 'f2', 8 => 'f2', 9 => 'f1', 12 => 'f2', 13 => 'f1', 14 => 'f2', 15 => 'f3', 17 => 'f3', 18 => 'f2', -18 => 'f2', -17 => 'f3', -15 => 'f3', -14 => 'f2', -13 => 'f1', -12 => 'f2', -9 => 'f2', -8 => 'f3', -7 => 'f4', -5 => 'f3', -4 => 'f2', -3 => 'f1', -2 => 'f4', -1 => 'f3', ), -18 => array ( 0 => 'f1+115', 1 => 'f3', 2 => 'f4', -2 => 'f4', -1 => 'f2', ), -17 => array ( 1 => 'f3', 2 => 'f3', -2 => 'f3', -1 => 'f3', ), -16 => array ( 2 => 'f2', -2 => 'f2', ), -15 => array ( 1 => 'f2', 2 => 'f2', -2 => 'f2', -1 => 'f2', ), -14 => array ( 0 => 'f1', 1 => 'f1', 2 => 'f1', -2 => 'f1', -1 => 'f1', ), -13 => array ( 0 => 'f1', 1 => 'f1', 2 => 'f2', -2 => 'f2', -1 => 'f1', ), -12 => array ( 0 => 'f1', 1 => 'f3', 2 => 'f4', -2 => 'f4', -1 => 'f2', ), 10 => array ( 0 => 'f1', ), 11 => array ( 0 => 'f2', ), 12 => array ( 0 => 'f1', 1 => 'f3', 2 => 'f4', -2 => 'f4', -1 => 'f3', ), 13 => array ( 0 => 'f1', 1 => 'f1', 2 => 'f2', -2 => 'f2', -1 => 'f1', ), 14 => array ( 1 => 'f1', 2 => 'f2', -2 => 'f2', -1 => 'f1', ), 15 => array ( 2 => 'f1', -2 => 'f1', ), 16 => array ( 1 => 'f3', 2 => 'f2', -2 => 'f2', -1 => 'f2', ), 17 => array ( 0 => 'f1+115', 1 => 'f2', 2 => 'f4', -2 => 'f4', -1 => 'f2', ), )
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

    public function afterTurnWorldEvents($turn)
    {   
        if ($turn == 2) {
            $this->firstSpawn();
        } else {
            if ($turn % 20 === 0) {
                $this->level2Spawn();
            }
        }
    }
    
    public function firstSpawn()
    {
        $this->addOrks();
        $this->addBlueChests();
    }

    public function level2Spawn()
    {
        $this->addOrks();
        $this->addSkeletons();
        $this->addBlueChests();
        $this->addGoldChests();
    }

    protected function addBlueChests()
    {
        $cells = [
            [-4, -5], [-5, -4],
            [5, 4], [4, 5],
            [-5, 4], [-4, 5],
            [5, -4], [4, -5],
        ];
        $this->addObjectsToCells($cells, 111);
    }

    protected function addGoldChests()
    {
        $cells = [
            [0, -13],
            [13, 0],
            [0, 13],
            [-13, 0],
        ];
        $this->addObjectsToCells($cells, 112);
    }

    protected function addPurpleChest()
    {
        $cells = [
//            [-4, -5], [-5, -4],
//            [5, 4], [4, 5],
//            [-5, 4], [-4, 5],
//            [5, -4], [4, -5],
        // TODO
        ];
        $cells = [ChanceHelper::oneFromArray($cells)];
        $this->addObjectsToCells($cells, 111);
    }

    protected function addOrks()
    {
        $cells = [
            [-3, -3],
            [3, 3],
            [-3, 3],
            [3, -3],
        ];
        $this->addUnitsToCells($cells, 12);
    }

    protected function addSkeletons()
    {
        $cells = [
            [-2, -8], [-8, -2],
            [2, 8], [8, 2],
            [-2, 8], [8, -2],
            [2, -8], [-8, 2],
            [1, -14], [-1, -14],
            [1, 14], [-1, 14],
            [14, 1], [14, -1],
            [-14, 1], [-14, -1],
        ];
        $this->addUnitsToCells($cells, 18);
    }

    protected function addUnitsToCells($cells, $unitId)
    {
        $mage = GameBuilder::getGame()->getMage();
        $mX   = $mage->getX();
        $mY   = $mage->getY();
        foreach ($cells as $cell) {
            $unit = $this->world->addUnit($unitId, $cell[0], $cell[1], Game::ANIMATION_STAGE_TURN_END_EFFECTS);
            if ($unit) {
                GameBuilder::animateEvent(Game::EVENT_NAME_UNIT_SPAWN, [
                    'targetX'   => $unit->getX() - $mX,
                    'targetY'   => $unit->getY() - $mY
                ], Game::ANIMATION_STAGE_TURN_END_EFFECTS_2);
            }
        }
    }

    protected function addObjectsToCells($cells, $objectId)
    {
        foreach ($cells as $cell) {
            $object = $this->world->addObject($objectId, $cell[0], $cell[1]);
            if ($object) {
                GameBuilder::animateEvent(Game::EVENT_NAME_ADD_OBJECT,
                    ['object' => $object->exportForView()],
                    Game::ANIMATION_STAGE_TURN_END_EFFECTS_2);
            }
        }
    }
}