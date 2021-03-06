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
namespace Ilfate\WhiteHorde;
use Ilfate\User;

/**
 * TODO: Short description.
 * TODO: Long description here.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @link      http://ilfate.net
 */
abstract class WH
{

    /**
     * @var Settlement
     */
    protected static $settlement;

    /**
     * @var WHCharacter[]
     */
    protected static $characters = [];
    protected static $allCharactersLoaded = false;

    /**
     * @var WHBuilding[]
     */
    protected static $buildings = [];
    protected static $allBuildingsLoaded = false;

    protected static $actions = [];
    
    
    /**
     * @return Settlement
     */
    public static function getOrCreateSettlement()
    {
        if (self::$settlement) return self::$settlement;
        $user = User::getUser();
        self::$settlement = $user->settlement()->first();
        if (self::$settlement)  {
            self::$settlement->init();
            return self::$settlement;
        }
        self::$settlement = new Settlement();
        self::$settlement->user_id = $user->id;
        self::$settlement->age = 0;
        self::$settlement->save();
        return self::$settlement;
    }

    public static function getCharacter($id, $isCheckCharacter = true)
    {
        if (!empty(self::$characters[$id])) return self::$characters[$id];
        self::$characters[$id] = WHCharacter::findOrFail($id);
        self::$characters[$id]->init();
        if ($isCheckCharacter) {
            $settlement = WH::getOrCreateSettlement();
            if (!self::$characters[$id] || !$settlement || self::$characters[$id]->settlement_id !== $settlement->id) {
                throw new WHErrorException('Character with ID=' . $id . ' not found');
            }
        }
        return self::$characters[$id];
    }

    public static function getBuilding($id, $isCheckBuilding = true)
    {
        if (!empty(self::$buildings[$id])) return self::$buildings[$id];
        $tempBuilding = WHBuilding::findOrFail($id);
        if ($isCheckBuilding) {
            $settlement = WH::getOrCreateSettlement();
            if (!$settlement || $tempBuilding->settlement_id !== $settlement->id) {
                throw new WHErrorException('Building with ID=' . $id . ' not found');
            }
        }
        self::$buildings[$id] = $tempBuilding->make();
        return self::$buildings[$id];
    }

    public static function getAllBuildings()
    {
        if (self::$allBuildingsLoaded) {
            return self::$buildings;
        }
        $id = self::getOrCreateSettlement()->id;
        $buildings = WHBuilding::where('settlement_id', $id)->get();
        foreach ($buildings as $tempBuilding) {
            self::$buildings[$tempBuilding->id] = $tempBuilding->make();
        }
        self::$allBuildingsLoaded = true;
        return self::$buildings;
    }

    public static function getAllCharacters()
    {
        if (self::$allCharactersLoaded) {
            return self::$characters;
        }
        $id = self::getOrCreateSettlement()->id;
        $characters = WHCharacter::where('settlement_id', $id)->get();
        foreach ($characters as $character) {
            $character->init();
            self::$characters[$character->id] = $character;
        }
        self::$allCharactersLoaded = true;
        return self::$characters;
    }

    public static function exportAllCharacters()
    {
        $export = [];
        $all = self::getAllCharacters();
        foreach ($all as $character) {
            $export[] = $character->export();
        }
        return $export;
    }

    public static function exportAllBuildings()
    {
        $export = [];
        $all = self::getAllBuildings();
        foreach ($all as $building) {
            $export[] = $building->export();
        }
        return $export;
    }

    public static function addBuilding($type)
    {
        $building = WHBuilding::make($type);
        $building->settlement_id = self::getOrCreateSettlement()->id;
        $building->save();
//        $building->wasUpdated();
        self::$buildings[$building->id] = $building;
        return $building;
    }

    public static function addCharacter()
    {
        $character = new WHCharacter();
        $character->settlement_id = self::getOrCreateSettlement()->id;
        $character->location = WHCharacter::LOCATION_SETTLEMENT;
        $character->birth();
        $character->save();
//        $character->wasUpdated();
        self::$characters[$character->id] = $character;
        return $character;
    }
    
    public static function endExecution()
    {
        foreach (self::$characters as $character) {
            if ($character->isUpdated()) {
                $character->save();
            }
        }
        foreach (self::$buildings as $building) {
            if ($building->isUpdated()) {
                $building->save();
            }
        }
        if (self::$settlement->isUpdated()) {
            self::$settlement->save();
        }
    }

    public static function addAction($action, $arguments = [])
    {
        self::$actions[] = ['action' => $action, 'arguments' => $arguments];
    }

    public static function getActions()
    {
        return self::$actions;
    }
}