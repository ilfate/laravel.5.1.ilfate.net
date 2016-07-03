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
abstract class MapObject
{
    const ID = '0';

    protected $x;
    protected $y;
    protected $d;
    protected $data;
    protected $type;
    protected $config;
    protected $viewData = [];

    protected $isPassable = true;

    /**
     * @var Game
     */
    protected $game;
    /**
     * @var World
     */
    protected $world;

    protected $exist = true;

    public function __construct(World $world, $type, $x, $y, $id = null, $data = null)
    {
        if (!$id) {
            $this->id = str_random(10);
        } else {
            $this->id = $id;
        }
        $this->x = $x;
        $this->y = $y;
        if ($data) {
            $this->data = $data;
        }
        $this->world = $world;
        $this->type = $type;
        $this->config = \Config::get('mageSurvival.objects.list.' . $type);
    }

    /**
     * @param       $x
     * @param       $y
     * @param World $world
     *
     * @return MapObject
     * @throws \Exception
     *
     */
    public static function getRandomObject($x, $y, World $world)
    {
        $config = \Config::get('mageSurvival.objects');
        $chances = $config['chances'][$world->getType()];
        $distance = abs($x) + abs($y);
        foreach ($chances as $minDistance => $objectChances) {
            if ($distance < $minDistance) {
                if (!$objectChances) {
                    return false;
                }
                $objectType = ChanceHelper::oneFromArray($objectChances);
                $className = '\Ilfate\MageSurvival\MapObjects\\' . $config['list'][$objectType]['class'];
                return new $className($world, $objectType, $x, $y);
            }
        }
        throw new \Exception('You went so far that you found the end of the world and crashed my server. You have to talk to administrator to fix that problem');
    }

    /**
     * @param       $x
     * @param       $y
     * @param       $objectType
     * @param World $world
     *
     * @return MapObject
     */
    public static function getObject($x, $y, $objectType, World $world)
    {
        $config = \Config::get('mageSurvival.objects.list.' . $objectType);
        if (empty($config)) {
            throw new \Exception('Object with type "' . $objectType . '" not found in config');
        }
        $className = '\Ilfate\MageSurvival\MapObjects\\' . $config['class'];
        return new $className($world, $objectType, $x, $y);
    }

    public function getActions()
    {
        return [];
    }

    /**
     * @param $data
     *
     * @return self
     */
    public static function createObjectFromData(World $world, $x, $y, $data)
    {
        $config = \Config::get('mageSurvival.objects');
        $className = '\Ilfate\MageSurvival\MapObjects\\' . $config['list'][$data['type']]['class'];
        return new $className($world, $data['type'], $x, $y, $data['id'], $data['data']);
    }

    public function export()
    {
        return [
            'id' => $this->getId(),
            'data' => $this->getData(),
            'type' => $this->getType(),
        ];
    }

    public function exportForView()
    {
        list($x, $y) = GameBuilder::getRelativeCoordinats($this->x, $this->y);
        return [
            'id' => $this->getId(),
            'x' => $x,
            'y' => $y,
            'data' => $this->getData(),
            'viewData' => $this->viewData,
            'type' => $this->getType(),
            'config' => $this->config,
        ];
    }
    
    public function activate()
    {
        
    }

    public function getId()
    {
        return $this->id;
    }

    public function delete($stage = Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT)
    {
        $this->exist = false;
        $this->world->deleteObject($this->x, $this->y);
        GameBuilder::animateEvent(Game::EVENT_NAME_OBJECT_DESTROY, ['id' => $this->getId()], $stage);
    }

    public function update()
    {
        $this->world->updateObject($this);
    }

    public function move($x, $y, $stage = Game::ANIMATION_STAGE_UNIT_ACTION)
    {
        $oldX = $this->x;
        $oldY = $this->y;
//        $event = Event::trigger(Event::EVENT_UNIT_BEFORE_MOVE, ['owner' => $this]);
//        if (!empty($event['no-move'])) {
//            return;
//        }
        $mage = GameBuilder::getGame()->getMage();
        $wasOutside = $this->world->isOutSideOfViewArea($this->x, $this->y, $mage);
        $this->x = $x;
        $this->y = $y;
        //$isOutside = $this->world->isOutSideOfViewArea($this->x, $this->y, $this->mage);
        $this->world->moveObject($oldX, $oldY, $x, $y);
//        if ($isOutside) {
//            return;
//        }
        if ($wasOutside) {
            // unit was outside of view area but now entered the view
            GameBuilder::animateEvent(Game::EVENT_NAME_OBJECT_MOVE, [
                'id' => $this->getId(),
                'x' => $x - $mage->getX(), 'y' => $y - $mage->getY(),
                'oldX' => $oldX - $mage->getX(), 'oldY' => $oldY - $mage->getY(),
                'data' => $this->exportForView(),
            ], $stage);
        } else {
            GameBuilder::animateEvent(Game::EVENT_NAME_OBJECT_MOVE, [
                'x' => $x - $mage->getX(), 'y' => $y - $mage->getY(), 'id' => $this->getId()
            ], $stage);
        }
    }

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param Game $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    public function isPassable()
    {
        return $this->isPassable;
    }

    /**
     * @return boolean
     */
    public function isExist()
    {
        return $this->exist;
    }

    public function open(Mage $mage)
    {
        $possibleLoot = $this->config['loot'];
        $numberOfItems = 1;
        if (!empty($this->config['quantity'])) {
            $numberOfItems = $this->config['quantity'];
        }
        $foundItems = [];
        for ($i = 0; $i < $numberOfItems; $i++) {
            $itemId = ChanceHelper::oneFromArray($possibleLoot);
            //GameBuilder::message('Congratulations! You found item :item', '', ['data' => ['item' => $itemId]]);
            $mage->addItem($itemId);
            $foundItems[] = $itemId;
        }
        $this->delete();
        return ['action' => 'itemsFound', 'data' => $foundItems];
    }
}