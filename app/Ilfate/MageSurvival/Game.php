<?php
/**
 * TODO: Package description.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @copyright 2016 Watchmaster GmbH
 * @license   Proprietary license.
 * @link      http://www.watchmaster.de
 */
namespace Ilfate\MageSurvival;
use Ilfate\Mage;
use Ilfate\User;

/**
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
class Game
{
    const STATUS_GAME_INIT = 'game_init';
    const STATUS_BATTLE = 'mage_battle';

    const ACTION_MOVE = 'move';
    const ACTION_ROTATE = 'rotate';

    protected $player;

    protected $config;

    protected $status = self::STATUS_GAME_INIT;

    /**
     * @var World
     */
    protected $world;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var \Ilfate\MageSurvival\Mage
     */
    protected $mage;

    protected $isMapUpdated = false;

    /**
     * @var WorldGenerator
     */
    private $worldGenerator;

    public function __construct()
    {
        $this->config = \Config::get('mageSurvival');
    }

    public function getViewName()
    {
        if (isset($this->config['status-to-page'][$this->status])) {
            $pageName = $this->config['status-to-page'][$this->status];
            return $this->config['pages'][$pageName];
        }
    }

    public function getData()
    {
        $data = [];
        $data['map'] = $this->worldGenerator->exportMapForView($this->mage);
        $data['world'] = $this->config['world-types'][$this->world->getType()];
        $data['mage'] = $this->mage->viewExport();

        return ['game' => $data];
    }

    public function action($action, $data)
    {
        $data = [];
        switch ($action) {
            case self::ACTION_MOVE:
                $this->mage->move($data);
                $this->isMapUpdated = true;

                break;
            case self::ACTION_ROTATE:
                $this->mage->rotate($data);
                break;
            default:
                throw new \Exception('Action "' . $action . '" do not exist');
                break;
        }
        if ($this->isMapUpdated) {
            $data['map'] = $this->worldGenerator->exportMapForView($this->mage);
        }
        return ['action' => $action, 'game' => $data];
    }

    public function initWorld()
    {
        $this->worldGenerator->init();
    }

    public function createMageByMageEntity(Mage $mageEntity)
    {
        $class = $mageEntity->class;
        $name = 'Ilfate\MageSurvival\Mages\\' . $this->config['mages-types'][$class]['name'];
        return new $name($mageEntity);
    }

    /**
     * @param World $world
     * @param \Ilfate\MageSurvival\Mage  $mage
     *
     * @return WorldGenerator
     * @throws \Exception
     */
    public function getGenerator(World $world, \Ilfate\MageSurvival\Mage $mage)
    {
        if (empty($this->config['world-types'][$world->getType()])) {
            throw new \Exception('Generator for world type "' . $world->getType() . '" not configured');
        }
        $name = '\Ilfate\MageSurvival\Generators\WorldGenerator' . $this->config['world-types'][$world->getType()];
        return new $name($world, $mage);
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param World $world
     */
    public function setWorld($world)
    {
        if (empty($this->mage)) {
            throw new \Exception('Mage need to be in Game before World could be set');
        }
        $this->worldGenerator = $this->getGenerator($world, $this->mage);
        $this->world = $world;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Mage
     */
    public function getMage()
    {
        return $this->mage;
    }

    /**
     * @param Mage $mage
     */
    public function setMage($mage)
    {
        $this->mage = $mage;
    }
}