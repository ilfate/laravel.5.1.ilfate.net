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
use Ilfate\MageUser;
use Ilfate\MageWorld;
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
    const STATUS_HOME = 'mage_home';

    const ACTION_MOVE            = 'move';
    const ACTION_ROTATE          = 'rotate';
    const ACTION_OBJECT_INTERACT = 'objectInteract';
    const ACTION_CRAFT_SPELL     = 'craftSpell';
    const ACTION_SPELL           = 'spell';

    const EVENT_NAME_MAGE_ROTATE     = 'mage-rotate';
    const EVENT_NAME_UNIT_MOVE       = 'unit-move';
    const EVENT_NAME_UNIT_ATTACK     = 'unit-attack';
    const EVENT_NAME_MAGE_SPELL_CAST = 'mage-spell-cast';
    const EVENT_NAME_OBJECT_DESTROY  = 'object-destroy';
    const EVENT_NAME_MAGE_DAMAGE     = 'mage-damage';
    const EVENT_NAME_MAGE_HEAL       = 'mage-heal';
    const EVENT_NAME_MAGE_ADD_ARMOR  = 'mage-add-armor';
    const EVENT_NAME_MAGE_USE_PORTAL = 'mage-use-portal';
    const EVENT_NAME_UNIT_DAMAGE     = 'unit-damage';
    const EVENT_NAME_ADD_OBJECT      = 'add-object';
    const EVENT_NAME_ADD_UNIT        = 'add-unit';
    const EVENT_NAME_SPELL_CRAFT     = 'spell-craft';
    const EVENT_NAME_OBJECT_ACTIVATE = 'object-activate';
    const EVENT_CELL_CHANGE          = 'cell-change';

    const ANIMATION_STAGE_MAGE_ACTION = 'mage-action';
    const ANIMATION_STAGE_MAGE_ACTION_2 = 'mage-action-2';
    const ANIMATION_STAGE_MAGE_ACTION_3 = 'mage-action-3';
    const ANIMATION_STAGE_MAGE_ACTION_EFFECT = 'mage-action-effect';
    const ANIMATION_STAGE_MAGE_ACTION_EFFECT_2 = 'mage-action-effect-2';
    const ANIMATION_STAGE_UNIT_ACTION = 'unit-action';
    const ANIMATION_STAGE_UNIT_ACTION_2 = 'unit-action-2';
    const ANIMATION_STAGE_UNIT_ACTION_3 = 'unit-action-3';
    const ANIMATION_STAGE_TURN_END_EFFECTS = 'turn-end-effects';

    public static $stagesList = [
        self::ANIMATION_STAGE_MAGE_ACTION,
        self::ANIMATION_STAGE_MAGE_ACTION_2,
        self::ANIMATION_STAGE_MAGE_ACTION_3,
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
    protected $inactiveMages = [];
    /**
     * @var MageUser
     */
    protected $mageUser;
    protected $mageUserUpdated = false;
    protected $userFlags;

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
        $data['world'] = $this->config['worlds'][$this->world->getType()]['map-type'];
        $data['mage'] = $this->mage->viewExport();
        $data['item-types'] = $this->mage->getItemsConfig()['item-types'];
        $data['actions'] = $this->mage->getAllPossibleActions($this->world);
        $data['turn'] = $this->getTurn();
        $data['config'] = [
            'screenRadius' => $this->worldGenerator->getScreenRadius(),
            'cellSize' => 1.6,
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


        $this->nextTurn();
        $return['turn'] = $this->getTurn();
        $this->save();

        $return['actions'] = $this->mage->getAllPossibleActions($this->world);
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

            $active = $this->worldGenerator->getActiveUnitsAndObjects($this->mage);
            foreach ($active['units'] as $activeUnit) {
                if ($activeUnit->isAlive()) {
                    $activeUnit->activate();
                }
            }
            foreach ($active['objects'] as $activeObject) {
                if ($activeObject->isExist()) {
                    $activeObject->activate();
                }
            }
        }
    }

    public function turn()
    {
        $this->isTurnHappend = true;
    }

    protected function save()
    {
        if (Event::isUpdated()) {
            $this->world->setEvents(Event::export());
        }
        $this->mage->saveIfUpdated();
        $this->world->saveIfChanged();
        if ($this->mageUserUpdated) {
            $this->mageUser->flags = json_encode($this->getUserFlags());
            $this->mageUser->save();
        }
    }

    public function getTurn()
    {
        return $this->mage->getTurn();
    }

    public function initWorld()
    {
        $this->worldGenerator->init();
    }

    public function createMageByMageEntity(Mage $mageEntity)
    {
        $class = $mageEntity->class;
        $name = 'Ilfate\MageSurvival\Mages\\' . $this->config['mages-types'][$class]['name'];
        return new $name($mageEntity, $this);
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
        if (empty($this->config['worlds'][$world->getType()])) {
            throw new \Exception('Generator for world type "' . $world->getType() . '" not configured');
        }
        $name = '\Ilfate\MageSurvival\Generators\WorldGenerator' . $this->config['worlds'][$world->getType()]['map-type'];
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

    public function getUserFlag($flagName)
    {
        if (!$this->userFlags) {
            $this->loadFlags();
        }
        if (isset($this->userFlags[$flagName])) {
            return $this->userFlags[$flagName];
        }
        return null;
    }

    public function getUserFlags()
    {
        if (!$this->userFlags) {
            $this->loadFlags();
        }
        return $this->userFlags;
    }
    protected function loadFlags()
    {
        $rawFlags = $this->getMageUser()->flags;
        $flags = [];
        if ($rawFlags) {
            $flags = json_decode($rawFlags, true);
        }
        $this->userFlags = $flags;
    }

    public function setUserFlag($name, $value)
    {
        if (!$this->userFlags) {
            $this->loadFlags();
        }
        $this->userFlags[$name] = $value;
        $this->mageUserUpdated();
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

    public function getHomeData() {
        $data = [];
        $data['mage'] = $this->mage->viewExport();
        $data['item-types'] = $this->mage->getItemsConfig()['item-types'];
//        $data['worlds'] = $this->config['worlds'];
        $data['available-worlds'] = $this->getAvailableWorldsList();

        return ['game' => $data];
    }

    public function setInactiveMages($allMages)
    {
        $this->inactiveMages = $allMages;
    }

    /**
     * @return array
     */
    public function getInactiveMages()
    {
        return $this->inactiveMages;
    }

    public function setWorldType($name)
    {
        if ($this->status !== self::STATUS_HOME) {
            throw new \Exception('Wrong status');
        }
        $this->status = self::STATUS_BATTLE;
        $allWorlds = \Config::get('mageSurvival.worlds');
        $type = 0;
        foreach ($allWorlds as $typeId => $worldConfig) {
            if ($worldConfig['map-type'] == $name) {
                $type = $typeId;
                break;
            }
        }
        if (!$type) {
            throw new \Exception('Wrong world type');
        }
        $existingWorld = MageWorld::where('type', '=', $type)->where('player_id', '=', User::getUser()->id)->get()->first();
        if ($existingWorld) {
            $mageEntity = $this->mage->getMageEntity();
            $mageEntity->world_id = $existingWorld->id;
            $mageEntity->save();
            $world = new World($existingWorld);
            $this->setWorld($world);
            $this->worldGenerator->mageEnter();
        } else {
            GameBuilder::createWorld($this, $this->mage->getMageEntity(), $type);
        }
        return true;
    }

    public function getAvailableWorldsList()
    {
        $allWorlds = \Config::get('mageSurvival.worlds');
        $flags = $this->getUserFlags();
        $available = [];
        foreach ($allWorlds as $world) {
            if (($world['is-available']
                && (empty($flags[$world['map-type']]) || $flags[$world['map-type']] != 'closed'))
                ||
                (!empty($flags[$world['map-type']]) && $flags[$world['map-type']] == 'open')
            ) {
                $available[] = $world;
            }
        }
        return $available;
    }

    public function setMageUser(MageUser $mageUser)
    {
        $this->mageUser = $mageUser;
    }

    /**
     * @return MageUser
     */
    public function getMageUser()
    {
        return $this->mageUser;
    }

    public function mageUserUpdated()
    {
        $this->mageUserUpdated = true;
    }

    public function addAllSpells()
    {
        $config = \Config::get('mageSpells');
        foreach ($config['list'] as $schoolId => $school) {
            foreach ($school as $number => $spell) {
                Spell::addSpell(
                    $spell['class'],
                    $config['schools'][$schoolId]['name'],
                    $number,
                    $schoolId,
                    10);
            }
        }
        $this->mage->save();
    }
}