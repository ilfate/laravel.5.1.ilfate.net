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
abstract class Unit implements AliveInterface
{
    const DATA_KEY_IS_HOSTILE = 'is_h';

    const TEAM_TYPE_HOSTILE = 'h';
    const TEAM_TYPE_FRIENDLY = 'f';
    const TEAM_TYPE_NEUTRAL = 'n';

    const CONFIG_KEY_BEHAVIOUR = 'behaviour';
    const CONFIG_KEY_SECONDARY_BEHAVIOUR = 'secondaryBehaviour';

    protected $id;
    protected $type;
    protected $x;
    protected $y;
    protected $d;
    protected $was;
    protected $data;

    /**
     * By default team is enemies. Units aggresive to mage
     * it also could be "f" = > friendly
     * it also could be "n" = > neitral
     * @var string
     */
    protected $team;
    
    protected $alive = true;

    /**
     * @var Game
     */
    protected $game;
    /**
     * @var World
     */
    protected $world;
    /**
     * @var Mage
     */
    protected $mage;
    /**
     * @var array
     */
    protected $behaviourNames;
    /**
     * @var Behaviour[]
     */
    protected $behaviours;

    protected $config;
    protected $currentBehaviour = 0;
    protected $temporaryData = [];

    public function __construct(World $world, Mage $mage, $x, $y, $type, $id = null, $data = null)
    {
        $this->config = \Config::get('mageUnits.list.' . $type);
        if (!$id) {
            // ok this is a new unit
            $this->id = str_random(10);
            $this->data = [
                'health' => $this->config['health'],
            ];
        } else {
            $this->id = $id;
        }
        $this->x = $x;
        $this->y = $y;
        $this->was = [
            'x' => $x,
            'y' => $y,
        ];
        if (!empty($this->config['team']) && $this->config['team'] !== self::TEAM_TYPE_HOSTILE) {
            $this->team = $this->config['team'];
        }
        if ($data) {
            $this->data = $data;
            if (!empty($data['d'])) {
                $this->d = $data['d'];
                $this->was['d'] = $data['d'];
            }
            if (!empty($data['health'])) {
                $this->was['health'] = $data['health'];
            }
            if (!empty($data['team'])) {
                $this->team = $data['team'];
            }
        }
        $this->world = $world;
        $this->mage  = $mage;
        $this->type  = $type;
        if (empty($data[self::CONFIG_KEY_BEHAVIOUR])) {
            $this->behaviourNames = $this->config[self::CONFIG_KEY_BEHAVIOUR];

        } else {
            $this->behaviourNames = $data[self::CONFIG_KEY_BEHAVIOUR];
        }
        if (!is_array($this->behaviourNames)) { $this->behaviourNames = [$this->behaviourNames]; }
        //$this->behaviour = $this->getBehaviour($behaviourName);
//        if (empty($this->config[self::CONFIG_KEY_SECONDARY_BEHAVIOUR])) {
//            $secondaryBehaviourName = $this->config[self::CONFIG_KEY_SECONDARY_BEHAVIOUR];
//            if (!empty($data[self::CONFIG_KEY_SECONDARY_BEHAVIOUR])) {
//                $secondaryBehaviourName = $data[self::CONFIG_KEY_SECONDARY_BEHAVIOUR];
//            }
//            $this->secondaryBehaviour = $this->getBehaviour($secondaryBehaviourName);
//        }
        $this->init();
    }

    protected function getBehaviour($behaviourNumber = false)
    {
        if ($behaviourNumber === false) {
            $behaviourNumber = $this->currentBehaviour;
        }
        if (empty($this->behaviourNames[$behaviourNumber])) {
            return false;
        }
        $behaviourName = $this->behaviourNames[$behaviourNumber]; // default behaviour
     
        if (!empty($this->behaviours[$behaviourName])) {
            return $this->behaviours[$behaviourName];
        }
        $behaviourClass = '\\Ilfate\\MageSurvival\\Behaviours\\' . $behaviourName;
        if (!class_exists($behaviourClass)) {
            throw new \Exception('Behaviour class missing ' . $behaviourClass);
        }
        $this->behaviours[$behaviourName] = new $behaviourClass($this);
        return $this->behaviours[$behaviourName];
    }

    /**
     * @param       $x
     * @param       $y
     * @param World $world
     * @param Mage  $mage
     *
     * @return Unit
     * @throws \Exception
     */
    public static function getRandomUnit($x, $y, World $world, Mage $mage)
    {
        $config = \Config::get('mageUnits');
        $chances = $config['chances'][$world->getType()];
        $distance = abs($x) + abs($y);
        $unitType = 0;
        foreach ($chances as $minDistance => $objectChances) {
            if ($distance < $minDistance) {
                $unitType = ChanceHelper::oneFromArray($objectChances);
                break;
            }
        }
        if (!$unitType) {
            throw new MessageException('You went so far that you found the end of the world and crashed my server. You have to talk to administrator to fix that problem');
        }
        return static::getUnit($x, $y, $unitType, $world, $mage);
    }

    /**
     * @param       $x
     * @param       $y
     * @param       $unitId
     * @param World $world
     * @param Mage  $mage
     *
     * @return Unit
     */
    public static function getUnit($x, $y, $unitId, World $world, Mage $mage)
    {
        $config = \Config::get('mageUnits');
        $className = '\Ilfate\MageSurvival\Units\\' . $config['list'][$unitId]['class'];
        return new $className($world, $mage, $x, $y, $unitId);
    }

    /**
     * @param $data
     *
     * @return self
     */
    public static function createUnitFromData(World $world, Mage $mage, $x, $y, $data)
    {
        $config = \Config::get('mageUnits.list');
        $className = '\Ilfate\MageSurvival\Units\\' . $config[$data['type']]['class'];
        return new $className($world, $mage, $x, $y, $data['type'], $data['id'], $data['data']);
    }

    protected function init()
    {

    }

    public function export()
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType(),
            'data' => $this->getData(),
        ];
    }
    public function exportForView()
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType(),
            'data' => $this->getData(),
            'icon' => $this->config['icon'],
            'iconColor' => isset($this->config['iconColor']) ? $this->config['iconColor'] : '',
        ];
    }

    /**
     * ACTIVATE
     */
    public function activate($action = null)
    {
        if (!$action) {
            $behaviour = $this->getBehaviour();
            if (!$behaviour) {
                return;
            }
            $action = $behaviour->getAction();
        }
        switch($action) {
            case Behaviour::ACTION_MOVE_TO_TARGET:
                $target = $this->getTemporaryDataValue('target');
                $nextMove = $this->world->getNextMoveToGetTo(
                    [$this->getX(), $this->getY()],
                    [$target->getX(), $target->getY()]
                );
                if ($nextMove === false) {
                    $this->activate(Behaviour::ACTION_DO_NOTHING);
                    break;
                }
                list($d, $x, $y) = $nextMove;
                $this->move($x, $y);
                break;
            case Behaviour::ACTION_JUMP_TO:
                $landing = $this->getTemporaryDataValue('landing');

                $this->move($landing[0], $landing[1]);
                break;
            case Behaviour::ACTION_ATTACK_MAGE:
                $possibleAttack = $this->getPossibleAttack($this->mage);
                if (!$possibleAttack) {
                    $this->addTemporaryDataValue('target', $this->mage);
                    $this->activate(Behaviour::ACTION_MOVE_TO_TARGET);
                    break;
                }
                $this->attack($possibleAttack, $this->mage);
                break;
            case Behaviour::ACTION_ATTACK_UNIT:
                $target = $this->getTemporaryDataValue('target');
                $possibleAttack = $this->getPossibleAttack($target);
                if (!$possibleAttack) {
                    $this->activate(Behaviour::ACTION_MOVE_TO_TARGET);
                    break;
                }
                $this->attack($possibleAttack, $target);
                break;
            case Behaviour::ACTION_DO_NOTHING:
                $this->currentBehaviour ++;
                $this->activate();
                break;
        }
    }

    public function attack($attackConfig, AliveInterface $target)
    {
        // well it should be already checked that attack is possible
        $target->damage($attackConfig['damage'], Game::ANIMATION_STAGE_UNIT_ACTION_3);
        $mX = $this->mage->getX();
        $mY = $this->mage->getY();
        GameBuilder::animateEvent(Game::EVENT_NAME_UNIT_ATTACK, [
            'attack' => $attackConfig,
            'targetX' => $target->getX() - $mX,
            'targetY' => $target->getY() - $mY,
            'fromX' => $this->getX() - $mX,
            'fromY' => $this->getY() - $mY
        ], Game::ANIMATION_STAGE_UNIT_ACTION_2);
    }

    public function getPossibleAttack($target)
    {
        $allAttacks = $this->config['attacks'];

        // check for cooldowns

        $distance = $this->world->getRealDistance($this, $target);
        $attackConfigs = [];
        foreach ($allAttacks as $attackName) {
            $attack = \Config::get('mageUnits.attacks.' . $attackName);
            $isPossible = true;
            if ($distance > $attack['range']) $isPossible = false;
            // check for possibility to perform this attack
            if (!$isPossible) {
                continue;
            }
            $attackConfigs[$attackName] = $attack;
        }

        if (!$attackConfigs) {
            return false;
        }
        $attackName = array_rand($attackConfigs);
        $attackConfig = $attackConfigs[$attackName];
        $attackConfig['name'] = $attackName;
        return $attackConfig;
    }

    public function move($x, $y, $stage = Game::ANIMATION_STAGE_UNIT_ACTION)
    {
        $oldX = $this->x;
        $oldY = $this->y;
        $wasOutside = $this->world->isOutSideOfViewArea($this->x, $this->y, $this->mage);
        $this->x = $x;
        $this->y = $y;
        $isOutside = $this->world->isOutSideOfViewArea($this->x, $this->y, $this->mage);
        $this->world->moveUnit($oldX, $oldY, $x, $y);
        if ($isOutside) {
            return;
        }
        if ($wasOutside) {
            // unit was outside of view area but now entered the view
            GameBuilder::animateEvent(Game::EVENT_NAME_UNIT_MOVE, [
                'x' => $x - $this->mage->getX(), 'y' => $y - $this->mage->getY(), 'id' => $this->getId(),
                'data' => $this->exportForView(), 'oldX' => $oldX - $this->mage->getX(), 'oldY' => $oldY - $this->mage->getY()
            ], $stage);
        } else {
            GameBuilder::animateEvent(Game::EVENT_NAME_UNIT_MOVE, [
                'x' => $x - $this->mage->getX(), 'y' => $y - $this->mage->getY(), 'id' => $this->getId()
            ], $stage);
        }
    }

    public function damage($value, $animationStage)
    {
        $this->data['health'] -= $value;
        if ($this->data['health'] < 1) {
            // Unit dead
            $this->world->destroyUnit($this->x, $this->y);
            $this->dead($animationStage);
        } else {
            // unit damage
            GameBuilder::animateEvent(Game::EVENT_NAME_UNIT_DAMAGE, [
                'id' => $this->getId(),
                'value' => $value
            ], $animationStage);
            if ($onDamageBehaviour = $this->getOnDamageBehaviour()) {
                $this->data[self::CONFIG_KEY_BEHAVIOUR] = $onDamageBehaviour;
            }
            $this->world->updateUnit($this);
        }
    }

    public function dead($animationStage)
    {
        $this->alive = false;
        GameBuilder::animateEvent('unit-kill', ['id' => $this->getId()], $animationStage);
        if (!empty($this->config['loot'])) {
            $object = $this->world->addObject($this->config['loot'], $this->getX(), $this->getY());
            if ($object) {
                GameBuilder::animateEvent(Game::EVENT_NAME_ADD_OBJECT,
                    ['object' => $object->exportForView()],
                    $animationStage);
            }
        }
        Event::trigger(Event::EVENT_UNIT_AFTER_DYING, ['owner' => $this]);
    }

    public function getOnDamageBehaviour() {
        return;
    }

    public function getType()
    {
        return $this->type;
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return Mage
     */
    public function getMage()
    {
        return $this->mage;
    }

    /**
     * @return World
     */
    public function getWorld()
    {
        return $this->world;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    public function isHostile()
    {
        if (!$this->team || $this->team == self::TEAM_TYPE_HOSTILE) {
            return true;
        }
        return false;
    }

    public function isFriendly()
    {
        if ($this->team == self::TEAM_TYPE_FRIENDLY) {
            return true;
        }
        return false;
    }

    public function getTeam()
    {
        if (!$this->team) {
            return self::TEAM_TYPE_HOSTILE;
        }
        return $this->team;
    }

    public function addDataValue($string, $value)
    {
        $this->data[$string] = $value;
    }
    public function addTemporaryDataValue($string, $value)
    {
        $this->temporaryData[$string] = $value;
    }
    public function getTemporaryDataValue($string)
    {
        return isset($this->temporaryData[$string]) ? $this->temporaryData[$string] : false;
    }

    /**
     * @return boolean
     */
    public function isAlive()
    {
        return $this->alive;
    }

}