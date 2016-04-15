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
    const KEY_TARGET = 'target';
    const KEY_OWNER = 'owner';
    const KEY_TIMES = 'times';

    const EVENT_UNIT_GET_DAMAGE = 'unit-get-damage';
    const EVENT_MAGE_BEFORE_GET_DAMAGE = 'mage-before-get-damage';
    const EVENT_MAGE_BEFORE_HEAL = 'mage-before-heal';
    const EVENT_MAGE_AFTER_MOVE = 'mage-after-move';

    protected static $withTarget = [
//        self::EVENT_UNIT_GET_DAMAGE,
    ];
    protected static $withOwner = [
        self::EVENT_UNIT_GET_DAMAGE
    ];
    protected static $bindings;
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
            list($class, $method) = explode(':', $eventData['action']);
            $class       = '\Ilfate\MageSurvival\Events\\' . $class;
            $triggerData = $class::$method($triggerData, $eventData['data']);
            if (isset($eventData['data'][self::KEY_TIMES])) {
                $eventData['data'][self::KEY_TIMES]--;
                self::update();
                if ($eventData['data'][self::KEY_TIMES] < 1) {
                    unset(self::$bindings[$key][$num]);
                }
            }
        }
        return $triggerData;
    }

    public static function getEventKey($eventName, $data)
    {
        $key = $eventName;
        if (in_array($eventName, self::$withTarget)) {
            $key .= '-t-' . $data['target']->getId();
        }
        if (in_array($eventName, self::$withOwner)) {
            $key .= '-o-' . $data['target']->getId();
        }
        return $key;
    }

    public static function create($eventName, $data, $action)
    {
        $key = self::getEventKey($eventName, $data);
        if (empty(self::$bindings[$key])) {
            self::$bindings[$key] = [];
        }
        if (isset($data[self::KEY_TARGET])) {
            unset($data[self::KEY_TARGET]);
        }
        if (isset($data[self::KEY_OWNER])) {
            unset($data[self::KEY_OWNER]);
        }
        self::$bindings[$key][] = ['action' => $action, 'data' => $data];
        self::update();
        return $key;
    }

    public static function export() {
        return self::$bindings;
    }

    public static function import($actions) {
        self::$bindings = $actions;
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