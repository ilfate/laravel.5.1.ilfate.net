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
 * @property $data
 * @property World $world
 *
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
abstract class AliveCommon
{
    /**
     * @var World
     */
    protected $world;
    
    const DATA_FLAG_KEY = 'f';
    const DATA_STAT_KEY = 's';
    const DATA_BUFF_KEY = 'b';

    const FLAG_FROZEN = 'frozen';
    const FLAG_FROZE_IMMUNE = 'froze_immune';
    const FLAG_BURN = 'burn';
    const FLAG_WEB = 'web';
    const FLAG_WATER_BODY = 'water-body';
    const FLAG_QUICKSAND = 'quicksand';
    const FLAG_STONED = 'stoned';
    const FLAG_STONE_IMMUNE = 'stone_immune';

    const UNIT_TYPE_UNIT = 'unit';
    const UNIT_TYPE_MAGE = 'mage';

    protected $haveSaidSomething = false;
    protected $alive = true;

    abstract public function update();
    abstract public function damage($value, $animationStage, $sourceType);
    abstract public function getId();

    /**
     * @return boolean
     */
    public function isAlive()
    {
        return $this->alive;
    }

    public function addFlag($flag, $value = true)
    {
        $this->data[self::DATA_FLAG_KEY][$flag] = $value;
        $this->update();
    }
        
    public function removeFlag($flag)
    {
        unset($this->data[self::DATA_FLAG_KEY][$flag]);
        $this->update();
    }

    public function getFlag($flag)
    {
        if (isset($this->data[self::DATA_FLAG_KEY][$flag])) {
            return $this->data[self::DATA_FLAG_KEY][$flag];
        }
        return false;
    }
    
    abstract public function getUnitType();

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
     * @return World
     */
    public function getWorld()
    {
        return $this->world;
    }

    /**
     * @param World $world
     */
    public function setWorld($world)
    {
        $this->world = $world;
    }
    
    public function say($message, $stage = Game::ANIMATION_STAGE_MESSAGE_TIME, $options = [])
    {
        if (!$message) return;
        $this->haveSaidSomething = true;
        if ($this->getUnitType() == self::UNIT_TYPE_MAGE) {
            $x = 0;
            $y = 0;
        } else {
            $mage = GameBuilder::getGame()->getMage();
            $x = $this->getX() - $mage->getX();
            $y = $this->getY() - $mage->getY();
        }
        $time = $showTime = (strlen($message) * 30) + 300;
        if (!empty($options['showTime'])) {
            $showTime *= $options['showTime'];
        }
        GameBuilder::animateEvent(Game::EVENT_NAME_SAY_MESSAGE, [
            'message' => $message, 'time' => $time, 'showTime' => $showTime,
            'targetX' => $x, 'targetY' => $y
        ], $stage);
    }
    
    public function freeze($turns, $stage)
    {
        $now = GameBuilder::getGame()->getTurn();
        if ($this->getFlag(self::FLAG_FROZE_IMMUNE) > $now) {
            if (ChanceHelper::chance(10)) {
                GameBuilder::getGame()->getMage()->say('If I try to freeze some thing too often it is becoming immune to freezing for some time. Good to know.');
            }
            return;
        }
        Event::create(
            Event::EVENT_UNIT_BEFORE_TURN, [
            Event::KEY_TIMES => $turns,
            Event::KEY_OWNER => $this,
            Event::KEY_ON_COMPLETE => 'Water:RemoveFreeze'
        ],
            'Water:Freeze');
        $this->addFlag(Unit::FLAG_FROZEN);
        $this->addFlag(Unit::FLAG_FROZE_IMMUNE, $now + $turns + 3);
        if ($this->getUnitType() == self::UNIT_TYPE_UNIT) {
            GameBuilder::animateEvent(Game::EVENT_NAME_ADD_UNIT_STATUS,
                [
                    'flags' => [Unit::FLAG_FROZEN => true],
                    'id'    => $this->getId()
                ],
                $stage);
        } else {
            throw new MessageException('Well this is some thing new. Freeze on mage? Did not implement that');
        }
    }

    public function sand($turns, $stage)
    {
        if ($this->getFlag(Unit::FLAG_QUICKSAND)) {
            return false;
        }
        Event::create(
            Event::EVENT_UNIT_BEFORE_MOVE, [
                Event::KEY_TURNS => $turns,
                Event::KEY_OWNER => $this,
                Event::KEY_ON_COMPLETE => 'Earth:RemoveSand'
            ],
            'Earth:Sand');
        $this->addFlag(Unit::FLAG_QUICKSAND);

        if ($this->getUnitType() == self::UNIT_TYPE_UNIT) {
            GameBuilder::animateEvent(Game::EVENT_NAME_ADD_UNIT_STATUS,
                [
                    'flags' => [Unit::FLAG_QUICKSAND => true],
                    'id'    => $this->getId()
                ],
                $stage);
        } else {
            throw new MessageException('Well this is some thing new. Sand on mage? Did not implement that');
        }
        return true;
    }

    public function stone($turns, $stage)
    {
        $now = GameBuilder::getGame()->getTurn();
        if ($this->getFlag(self::FLAG_STONE_IMMUNE) > $now) {
            if (ChanceHelper::chance(10)) {
                GameBuilder::getGame()->getMage()->say('If I try to turn into stone some thing too often it is becoming immune to that for some time. Good to know.');
            }
            return;
        }
        Event::create(
            Event::EVENT_UNIT_BEFORE_TURN, [
            Event::KEY_TIMES => $turns,
            Event::KEY_OWNER => $this,
            Event::KEY_ON_COMPLETE => 'Earth:RemoveStone'
        ],
            'Earth:Stone');
        $this->addFlag(Unit::FLAG_STONED);
        $this->addFlag(Unit::FLAG_STONE_IMMUNE, $now + $turns + 3);
        if ($this->getUnitType() == self::UNIT_TYPE_UNIT) {
            GameBuilder::animateEvent(Game::EVENT_NAME_ADD_UNIT_STATUS,
                [
                    'flags' => [Unit::FLAG_STONED => true],
                    'id'    => $this->getId()
                ],
                $stage);
        } else {
            throw new MessageException('Well this is some thing new. Stone on mage? Did not implement that');
        }
    }
    
    public function burn($turns, $stage)
    {
        Event::create(
            Event::EVENT_UNIT_AFTER_TURN, [
            Event::KEY_TIMES => $turns,
            Event::KEY_OWNER => $this,
            Event::KEY_ON_COMPLETE => 'Fire:RemoveBurn'
        ],
            'Fire:Burn');
        $this->addFlag(Unit::FLAG_BURN);
        if ($this->getUnitType() == self::UNIT_TYPE_UNIT) {
            GameBuilder::animateEvent(Game::EVENT_NAME_ADD_UNIT_STATUS,
                [
                    'flags' => [Unit::FLAG_BURN => true],
                    'id'    => $this->getId()
                ],
                $stage);
        } else {
            throw new MessageException('Well this is some thing new. Burn on mage? Did not implement that');
        }
    }

    /**
     * @return mixed
     */
    public function getHaveSaidSomething()
    {
        return $this->haveSaidSomething;
    }
}