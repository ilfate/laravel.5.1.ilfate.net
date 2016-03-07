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

    /**
     * @var Game
     */
    protected $game;
    /**
     * @var World
     */
    protected $world;

    public function __construct(World $world, $x, $y, $data = null)
    {
        $this->x = $x;
        $this->y = $y;
        if ($data) {
            $this->data = $data;
        }
        $this->world = $world;
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
                $objectId = ChanceHelper::oneFromArray($objectChances);
                $className = '\Ilfate\MageSurvival\MapObjects\\' . $config['list'][$objectId]['class'];
                return new $className($world, $x, $y);
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
        $className = '\Ilfate\MageSurvival\MapObjects\\' . $config['list'][$data['id']]['class'];
        return new $className($world, $x, $y, $data['data']);
    }

    public function export()
    {
        return [
            'id' => $this->getId(),
            'data' => $this->getData()
        ];
    }

    public function getId()
    {
        $id = static::ID;
        if (!$id) {
            throw new \Exception('Id for object '. static::class . ' is not defined');
        }
        return $id;
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
}