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

    /**
     * @var Game
     */
    protected $game;
    /**
     * @var World
     */
    protected $world;

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
                $objectType = ChanceHelper::oneFromArray($objectChances);
                $className = '\Ilfate\MageSurvival\MapObjects\\' . $config['list'][$objectType]['class'];
                return new $className($world, $objectType, $x, $y);
            }
        }
        throw new \Exception('You went so far that you found the end of the world and crashed my server. You have to talk to administrator to fix that problem');
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

    public function getId()
    {
        return $this->id;
    }

    public function delete($stage = Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT)
    {
        $this->world->deleteObject($this->x, $this->y);
        GameBuilder::animateEvent(Game::EVENT_NAME_OBJECT_DESTROY, ['id' => $this->getId()], $stage);
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
}