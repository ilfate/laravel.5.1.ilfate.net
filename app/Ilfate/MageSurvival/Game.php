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

    const EVENT_NAME_MAGE_ROTATE     = 'mage-rotate';
    const EVENT_NAME_UNIT_MOVE       = 'unit-move';
    const EVENT_NAME_MAGE_SPELL_CAST = 'mage-spell-cast';
    const EVENT_NAME_OBJECT_DESTROY  = 'object-destroy';
    const EVENT_NAME_MAGE_DAMAGE     = 'mage-damage';

    const ANIMATION_STAGE_MAGE_ACTION = 'mage-action';
    const ANIMATION_STAGE_MAGE_ACTION_2 = 'mage-action-2';
    const ANIMATION_STAGE_MAGE_ACTION_EFFECT = 'mage-action-effect';
    const ANIMATION_STAGE_MAGE_ACTION_EFFECT_2 = 'mage-action-effect-2';
    const ANIMATION_STAGE_UNIT_ACTION = 'unit-action';
    const ANIMATION_STAGE_UNIT_ACTION_2 = 'unit-action-2';

    public static $stagesList = [
        self::ANIMATION_STAGE_MAGE_ACTION,
        self::ANIMATION_STAGE_MAGE_ACTION_2,
        self::ANIMATION_STAGE_MAGE_ACTION_EFFECT,
        self::ANIMATION_STAGE_MAGE_ACTION_EFFECT_2,
    ];

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

    protected $animationEvents = [];

    protected $isTurnHappend = false;
    protected $isMapUpdated = false;
    protected $isObjectsUpdated = false;
    protected $isMageMoved = false;
    protected $isPlayerMoved = false;
    protected $isMageUpdated = false;
    protected $isItemsUpdated = false;
    protected $isSpellsUpdated = false;
    protected $isUnitsUpdated = false;

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
        $data['units'] = $this->worldGenerator->exportVisibleUnits();
        $data['world'] = $this->config['world-types'][$this->world->getType()];
        $data['mage'] = $this->mage->viewExport();
        $data['item-types'] = $this->config['item-types'];
        $data['actions'] = $this->mage->getAllPossibleActions($this->world);
        $data['config'] = [
            'screenRadius' => $this->worldGenerator->getScreenRadius(),
            'cellSize' => 32,
        ];

        return ['game' => $data];
    }

    public function action($action, $data)
    {
        $return = [];
        switch ($action) {
            case self::ACTION_MOVE:
                $this->mage->moveAction($data);
                $this->turn();
                break;
            case self::ACTION_OBJECT_INTERACT:
                $result = $this->mage->interactWithObject($this->world, $data['method']);
                $return['data'] = $result['data'];
                $this->turn();
                break;
            case self::ACTION_CRAFT_SPELL:
                $this->mage->craftSpell($data);
                break;
            case self::ACTION_SPELL:
                $this->mage->castSpell($data);
                $this->turn();
                break;
            default:
                throw new \Exception('Action "' . $action . '" do not exist');
                break;
        }


//        if ($this->isMageMoved || $this->isMapUpdated) {
//            $return['map'] = $this->worldGenerator->exportMapForView($this->mage);
//        }
//        if ($this->isMageMoved || $this->isMageUpdated) {
//            $return['mage'] = $this->mage->exportMage();
//        }
        if ($this->isItemsUpdated) {
            $return['items'] = $this->mage->getUpdatedItems();
        }
//        if ($this->isObjectsUpdated) {
//            $return['objects'] = $this->worldGenerator->exportVisibleObjects();
//        }
//        if ($this->isUnitsUpdated) {
//            $return['units'] = $this->worldGenerator->exportVisibleUnits();
//        }
        if ($this->isSpellsUpdated) {
            $return['spells'] = $this->mage->getUpdatedSpells();
        }

        $return['actions'] = $this->mage->getAllPossibleActions($this->world);
        $this->nextTurn();

        $this->save();

        if ($this->messages) {
            $return['messages'] = $this->messages;
        }
        if ($this->animationEvents) {
            $return['events'] = $this->animationEvents;
        }

        return ['action' => $action, 'game' => $return];
    }

    public function nextTurn()
    {
        if ($this->isTurnHappend) {
            $this->mage->increaseTurn();

            $activeUnits = $this->worldGenerator->getActiveUnits($this->mage);
            foreach ($activeUnits as $activeUnit) {
                $activeUnit->activate();
            }
        }
    }

    public function turn()
    {
        $this->isTurnHappend = true;
    }

    protected function save()
    {
        $this->mage->saveIfUpdated();
        $this->world->saveIfChanged();
    }

    public function getTurn()
    {
        $this->mage->getTurn();
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

    /**
     * @param boolean $isObjectsUpdated
     */
    public function setIsObjectsUpdated($isObjectsUpdated = true)
    {
        $this->isObjectsUpdated = $isObjectsUpdated;
    }

    /**
     * @param boolean $isMageMoved
     */
    public function setIsMageMoved($isMageMoved = true)
    {
        $this->isMageMoved = $isMageMoved;
    }

    /**
     * @return boolean
     */
    public function isIsUnitsUpdated()
    {
        return $this->isUnitsUpdated;
    }

    /**
     * @param boolean $isUnitsUpdated
     */
    public function setIsUnitsUpdated($isUnitsUpdated)
    {
        $this->isUnitsUpdated = $isUnitsUpdated;
    }

    /**
     * @param      $name
     * @param      $data
     * @param bool $animationStage
     */
    public function addAnimationEvent($name, $data, $animationStage)
    {
        $newValue = ['name' => $name, 'data' => $data];

        if (!isset($this->animationEvents[$animationStage])) {
            $this->animationEvents[$animationStage] = [];
        }
        $this->animationEvents[$animationStage][] = $newValue;
    }

    public function isAnimationOnStage($stage)
    {
        if (isset($this->animationEvents[$stage])) {
            return true;
        }
        return false;
    }

    /**
     * @return WorldGenerator
     */
    public function getWorldGenerator()
    {
        return $this->worldGenerator;
    }
}