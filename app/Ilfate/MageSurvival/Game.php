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

    const ACTION_MOVE            = 'move';
    const ACTION_ROTATE          = 'rotate';
    const ACTION_OBJECT_INTERACT = 'objectInteract';
    const ACTION_CRAFT_SPELL     = 'craftSpell';
    const ACTION_SPELL           = 'spell';

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
    protected $isPlayerMoved = false;
    protected $isMageUpdated = false;
    protected $isItemsUpdated = false;
    protected $isSpellsUpdated = false;

    /**
     * @var WorldGenerator
     */
    private $worldGenerator;

    protected $messages = [];

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
        $data['objects'] = $this->worldGenerator->exportVisibleObjects();
        $data['world'] = $this->config['world-types'][$this->world->getType()];
        $data['mage'] = $this->mage->viewExport();
        $data['actions'] = $this->mage->getAllPossibleActions($this->world);

        return ['game' => $data];
    }

    public function action($action, $data)
    {
        $return = [];
        switch ($action) {
            case self::ACTION_MOVE:
                $return = $this->mage->move($data);
                $this->worldGenerator->fillEmptyMap($return['map'], $this->mage);
                $return['objects'] = $this->worldGenerator->exportVisibleObjects();
                break;
            case self::ACTION_ROTATE:
                $return = $this->mage->rotate($data);
                break;
            case self::ACTION_OBJECT_INTERACT:
                $result = $this->mage->interactWithObject($this->world, $data['method']);
                $return['data'] = $result['data'];
                break;
            case self::ACTION_CRAFT_SPELL:
                $this->mage->craftSpell($data);
                break;
            case self::ACTION_SPELL:
                $this->mage->castSpell($data);
                break;
            default:
                throw new \Exception('Action "' . $action . '" do not exist');
                break;
        }

        if ($this->isMapUpdated) {
            $return['map'] = $this->worldGenerator->exportMapForView($this->mage);
        }
        $return['actions'] = $this->mage->getAllPossibleActions($this->world);
        if ($this->isItemsUpdated) {
            $return['items'] = $this->mage->getUpdatedItems();
        }
        if ($this->isSpellsUpdated) {
            $return['spells'] = $this->mage->getUpdatedSpells();
        }
        if ($this->messages) {
            $return['messages'] = $this->messages;
        }

        return ['action' => $action, 'game' => $return];
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
        $this->world->setGame($this);
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
     * @return \Ilfate\MageSurvival\Mage
     */
    public function getMage()
    {
        return $this->mage;
    }

    /**
     * @param \Ilfate\MageSurvival\Mage $mage
     */
    public function setMage($mage)
    {
        $this->mage = $mage;
        $this->mage->setGame($this);
    }

    public function updateMage()
    {
        $this->isMageUpdated = true;
    }

    public function updateMap()
    {
        $this->isMapUpdated = true;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param boolean $isItemsUpdated
     */
    public function setIsItemsUpdated($isItemsUpdated)
    {
        $this->isItemsUpdated = $isItemsUpdated;
    }

    /**
     * @param boolean $isSpellsUpdated
     */
    public function setIsSpellsUpdated($isSpellsUpdated)
    {
        $this->isSpellsUpdated = $isSpellsUpdated;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param array $message
     * @param null  $type
     * @param array $data
     */
    public function addMessage($message, $type = null, $data = [])
    {
        $m = ['message' => $message];
        if ($type) {
            $m['type'] = $type;
        }
        if ($data) {
            $m['data'] = $data;
        }
        $this->messages[] = $m;
    }

    /**
     * @param $messages
     */
    public function addMessages($messages)
    {
        $this->messages[] = array_merge($this->messages, $messages);
    }

    /**
     * @return World
     */
    public function getWorld()
    {
        return $this->world;
    }
}