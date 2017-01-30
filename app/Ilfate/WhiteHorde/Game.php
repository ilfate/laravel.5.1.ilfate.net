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
class Game
{

    public function loadMainScreen()
    {
        $data = [];
        
        $data['settlement'] = WH::getOrCreateSettlement()->export();
        $data['characters'] = WH::exportAllCharacters();
        $data['buildings'] = WH::exportAllBuildings();

        return $data;
    }

    public static function action($name, $data)
    {
        $return = [];
        $settlement = WH::getOrCreateSettlement();
        switch ($name) {
            case 'equipItem':
                WHCharacter::equipItemAction($data);
                break;
            case 'unequipItem':
                WHCharacter::unequipItemAction($data);
                break;
            case 'assignCharacter':
                WHBuilding::assignCharacter($data);
                break;
            case 'unassignCharacter':
                WHBuilding::unassignCharacter($data);
                break;
        }
        if ($changedResources = $settlement->exportChangedResources()) {
            $return['resources'] = $changedResources;
        }
        return $return;
    }
}