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
class Event
{
    const KEY_OWNER = 'owner';
    const KEY_TARGET = 'target';
    const KEY_TIMES = 'times';
    const KEY_TURNS = 'turns';
    const KEY_ON_COMPLETE = 'complete';

    const OWNER_SEPARATOR = '+o+';
    const TARGET_SEPARATOR = '+t+';

    const EVENT_UNIT_GET_DAMAGE = 'u-get-dmg';
    const EVENT_UNIT_BEFORE_DYING = 'u-b-die';
    const EVENT_UNIT_BEFORE_MOVE = 'u-b-move';
    const EVENT_UNIT_BEFORE_GET_DAMAGE = 'u-b-g-dmg';
    const EVENT_UNIT_AFTER_TURN = 'u-a-turn';
    const EVENT_UNIT_BEFORE_TURN = 'u-b-turn';
    const EVENT_UNIT_AFTER_ATTACK_MAGE = 'u-a-atk-mage';
    const EVENT_MAGE_AFTER_ATTACKED_BY_UNIT = 'm-a-atk-byunit';
    const EVENT_MAGE_BEFORE_GET_DAMAGE = 'm-b-get-dmg';
    const EVENT_MAGE_BEFORE_HEAL = 'm-b-heal';
    const EVENT_MAGE_AFTER_MOVE = 'm-a-move';
    const EVENT_MAGE_AFTER_TURN = 'm-a-turn';
    const EVENT_MAGE_AFTER_OBJECT_ACTIVATE = 'm-a-o-actv';

    protected static $withTarget = [
        self::EVENT_MAGE_AFTER_OBJECT_ACTIVATE,
    ];
    protected static $withOwner = [
        self::EVENT_UNIT_GET_DAMAGE,
        self::EVENT_UNIT_BEFORE_DYING,
        self::EVENT_UNIT_BEFORE_TURN,
        self::EVENT_UNIT_BEFORE_GET_DAMAGE,
        self::EVENT_UNIT_AFTER_TURN,
        self::EVENT_UNIT_BEFORE_MOVE,
        self::EVENT_UNIT_AFTER_ATTACK_MAGE,
    ];
    protected static $bindings;
    protected static $index;
    protected static $isUpdated = false;

    public static function trigger($eventName, $triggerData = [])
    {
        if (in_array($eventName, self::$withTarget) && empty($triggerData['target'])) {
            throw new \Exception('Event "' . $eventName . '" needs target');
        }
        if (in_array($eventName, self::$withOwner) && empty($triggerData['owner'])) {
            throw new \Exception('Event "' . $eventName . '" needs owner');
        }
        $key = self::getEventKey($eventName, $triggerData);
        if (empty(self::$bindings[$key])) {
            return $triggerData;
        }
        foreach (self::$bindings[$key] as $num => &$eventData) {
            if (isset($eventData['data'][self::KEY_TURNS])) {
                if ($eventData['data'][self::KEY_TURNS] < GameBuilder::getGame()->getTurn()) {
                    self::update();
                    self::removeEvent($key, $num, $eventData, $triggerData);
                    continue;
                }
            }
            if ($eventData['action']) {
                list($class, $method) = explode(':', $eventData['action']);
                $class       = '\Ilfate\MageSurvival\Events\\' . $class;
                $triggerData = $class::$method($triggerData, $eventData['data']);
            }
            if (isset($eventData['data'][self::KEY_TIMES])) {
                $eventData['data'][self::KEY_TIMES]--;
                self::update();
                if ($eventData['data'][self::KEY_TIMES] < 1) {
                    self::removeEvent($key, $num, $eventData, $triggerData);
                }
            }
        }
        return $triggerData;
    }

    protected static function removeEvent($key, $num, $eventData, $triggerData)
    {
        if (!empty($eventData['data'][self::KEY_ON_COMPLETE])) {
            list($class, $method) = explode(':', $eventData['data'][self::KEY_ON_COMPLETE]);
            $class       = '\Ilfate\MageSurvival\Events\\' . $class;
            $class::$method($triggerData, $eventData['data']);
        }
        if (strpos($key, self::OWNER_SEPARATOR) !== false) {
            if (!empty(self::$index['o'][$triggerData['owner']->getId()])) {
                $indexArray = &self::$index['o'][$triggerData['owner']->getId()];
                unset($indexArray[array_search($key, $indexArray)]);
                if (!$indexArray) {
                    unset(self::$index['o'][$triggerData['owner']->getId()]);
                }
            }
        }
        unset(self::$bindings[$key][$num]);
        if (empty(self::$bindings[$key])) {
            unset(self::$bindings[$key]);
        }
    }

    public static function getEventKey($eventName, $data)
    {
        $key = $eventName;
        if (in_array($eventName, self::$withTarget)) {
            if (!is_object($data['target'])) {
                throw new \Exception('Event target not found for "' . $eventName .'"');
            }
            $key .= self::TARGET_SEPARATOR . $data['target']->getId();
        }
        if (in_array($eventName, self::$withOwner)) {
            if (!is_object($data['owner'])) {
                throw new \Exception('Event owner not found for "' . $eventName .'"');
            }
            $key .= self::OWNER_SEPARATOR . $data['owner']->getId();
        }
        return $key;
    }

    public static function create($eventName, $data, $action = false)
    {
        $key = self::getEventKey($eventName, $data);
        if (empty(self::$bindings[$key])) {
            self::$bindings[$key] = [];
        }
        if (isset($data[self::KEY_TARGET])) {
            unset($data[self::KEY_TARGET]);
        }
        if (isset($data[self::KEY_OWNER])) {
            $ownerId = $data[self::KEY_OWNER]->getId();
            if (empty(self::$index['o'][$ownerId])) {
                self::$index['o'][$ownerId] = [];
            }
            self::$index['o'][$ownerId][] = $key;

            unset($data[self::KEY_OWNER]);
        }
        if (isset($data[self::KEY_TURNS])) {
            $data[self::KEY_TURNS] += GameBuilder::getGame()->getTurn();
        }
        self::$bindings[$key][] = ['action' => $action, 'data' => $data];
        self::update();
        return $key;
    }

    public static function export() {
        return [
            'bindings' => self::$bindings,
            'index' => self::$index
        ];
    }

    public static function import($actions) {
        if (!empty($actions['bindings'])) {
            self::$bindings = $actions['bindings'];
        }
        if (!empty($actions['index'])) {
            self::$index = $actions['index'];
        }
    }

    /**
     * @return boolean
     */
    public static function isUpdated()
    {
        return self::$isUpdated;
    }

    /**
     * @param boolean $isUpdated
     */
    public static function update()
    {
        self::$isUpdated = true;
    }

    public static function removeEventsRelatedToUnit($unitId)
    {
        if (isset(self::$index['o'][$unitId])) {
            foreach (self::$index['o'][$unitId] as $key) {
                unset(self::$bindings[$key]);
            }
            unset(self::$index['o'][$unitId]);
        }

//        throw new \Exception('IMPLEMENT THAT');
    }

//    public static function getBindingsByKey($key)
//    {
//        $data = [];
//        if (strpos($key, '-o-')) {
//            list($key, $owner) = explode('-o-', $key);
//        }
//        if (strpos($key, '-t-')) {
//            list($key, $target) = explode('-t-', $key);
//        }
//    }
}