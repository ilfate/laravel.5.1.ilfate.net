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

    protected $id;
    protected $type;
    protected $x;
    protected $y;
    protected $d;
    protected $was;
    protected $data;

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
     * @var Behaviour
     */
    protected $behaviour;

    protected $config;

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
        if ($data) {
            $this->data = $data;
            if (!empty($data['d'])) {
                $this->d = $data['d'];
                $this->was['d'] = $data['d'];
            }
            if (!empty($data['health'])) {
                $this->was['health'] = $data['health'];
            }
        }
        $this->world = $world;
        $this->mage  = $mage;
        $this->type  = $type;
        if (empty($data['behaviour'])) {
            $behaviourName = $this->config['behaviour'];
        } else {
            $behaviourName = $data['behaviour'];
        }
        $behaviourClass = '\\Ilfate\\MageSurvival\\Behaviours\\' . $behaviourName;
        if (!class_exists($behaviourClass)) {
            throw new \Exception('Behaviour class missing ' . $behaviourClass);
        }
        $this->behaviour = new $behaviourClass($this);
        $this->init();
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
        foreach ($chances as $minDistance => $objectChances) {
            if ($distance < $minDistance) {
                $unitType = ChanceHelper::oneFromArray($objectChances);
                $className = '\Ilfate\MageSurvival\Units\\' . $config['list'][$unitType]['class'];
                return new $className($world, $mage, $x, $y, $unitType);
            }
        }
        throw new MessageException('You went so far that you found the end of the world and crashed my server. You have to talk to administrator to fix that problem');
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

    /**
     * ACTIVATE
     */
    public function activate($action = null)
    {
        if (!$action) {
            $action = $this->behaviour->getAction();
        }
        switch($action) {
            case Behaviour::ACTION_MOVE_TO_MAGE:
                $nextMove = $this->world->getNextMoveToGetTo(
                    [$this->getX(), $this->getY()],
                    [$this->mage->getX(), $this->mage->getY()]
                );
                if ($nextMove === false) {
                    $this->activate(Behaviour::ACTION_DO_NOTHING);
                    break;
                }
                list($d, $x, $y) = $nextMove;
                $this->move($x, $y);
                break;
            case Behaviour::ACTION_ATTACK_MAGE:
                $possibleAttack = $this->getPossibleAttack();
                if (!$possibleAttack) {
                    $this->activate(Behaviour::ACTION_MOVE_TO_MAGE);
                    break;
                }
                $this->attack($possibleAttack, $this->mage);
                break;
            case Behaviour::ACTION_DO_NOTHING:
                break;
        }
    }

    public function attack($attackConfig, AliveInterface $target)
    {
        // well it should be already checked that attack is possible
        $target->damage(1, Game::ANIMATION_STAGE_UNIT_ACTION_2);
    }

    public function getPossibleAttack()
    {
        $allAttacks = $this->config['attacks'];

        // check for cooldowns

        $attackConfigs = [];
        foreach ($allAttacks as $attackName) {
            $attack = \Config::get('mageUnits.attacks.' . $attackName);
            // check for possibility to perform this attack
            if (false /* check is attack possible here  */) {
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
                'data' => $this->export(), 'oldX' => $oldX - $this->mage->getX(), 'oldY' => $oldY - $this->mage->getY()
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
            $this->world->destroyUnit($this->x, $this->y);
            GameBuilder::animateEvent('unit-kill', ['id' => $this->getId()], $animationStage);
        } else {
            $this->world->updateUnit($this);
            GameBuilder::animateEvent(Game::EVENT_NAME_UNIT_DAMAGE, [
                'id' => $this->getId(),
                'value' => $value
            ], $animationStage);
        }
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

}